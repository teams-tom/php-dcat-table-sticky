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
        $input['table_header'] = $input['table_header'] ?: 'close';
        $input['table_first'] = $input['table_first'] ?: 'close';
        $input['table_last'] = $input['table_last'] ?: 'close';

        return $input;
    }

    /**
     * 设置表单
     */
    public function form()
    {
        $this->radio('table_header', $this->trans('captcha.open_header_sticky'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('close');
//            ->help($this->trans('captcha.login_failure'));
        $this->radio('table_first', $this->trans('captcha.open_first_sticky'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('close');
        $this->radio('table_last', $this->trans('captcha.open_last_sticky'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('close');
        $this->radio('table_border', $this->trans('captcha.open_border'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('close');
        $this->radio('table_zebra', $this->trans('captcha.open_zebra'))
            ->options([
                'open' => $this->trans('captcha.open'),
                'close' => $this->trans('captcha.close'),
            ])
            ->default('close');
    }
}
