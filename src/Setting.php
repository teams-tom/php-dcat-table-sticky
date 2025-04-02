<?php

namespace TeamsTom\TableSticky;

use Dcat\Admin\Extend\Setting as Form;
use Dcat\Admin\Support\Helper;

class Setting extends Form
{
    /**
     * 设置标题
     */
    public function title()
    {
        return $this->trans('captcha.setting');
    }

    /**
     * 格式化
     */
    protected function formatInput(array $input)
    {
        $input['captcha_type'] = $input['captcha_type'] ?: 'string';
        $input['fontsize'] = $input['fontsize'] ?: 20;
        $input['codelen'] = $input['codelen'] ?: 4;
        $input['charset'] = $input['charset'] ?: 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789';
        $input['captcha_position'] = $input['captcha_position'] ?? '';
        $input['auto_refresh'] = $input['auto_refresh'] ?? 'open';

        return $input;
    }

    /**
     * 设置表单
     */
    public function form()
    {
        $this->radio('auto_refresh', $this->trans('captcha.open_header_sticky'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('open');
//            ->help($this->trans('captcha.login_failure'));
    }
}
