<?php

namespace TeamsTom\TableSticky;

use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Dcat\Admin\Extend\ServiceProvider as BaseServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\Support\Helper;
use Dcat\Admin\Traits\HasFormResponse;

class ServiceProvider extends BaseServiceProvider
{
	public function init()
	{
		parent::init();

        Admin::booting(function () {
            $loginPath = admin_base_path('auth/login') ;
            $loginPath = ($loginPath !== '/') ? trim ($loginPath,'/' ) : $loginPath;
            // 判断是否非登录页
            $isNotLoginPage = !Helper::matchRequestPath('get:'.$loginPath) && !Helper::matchRequestPath('post:'.$loginPath);
            if ($isNotLoginPage){
                // 这里添加非登录页需要执行的逻辑
                echo '<script>console.log("当前不是登录页，路径：'.addslashes($loginPath).'");</script>';
                // 示例：在非登录页注入脚本
                $script = '
                ;(function() {
                    tableDom = document.querySelector("table");
                    if(tableDom){
                        // 禁止html根结点纵向滚动
                        document.documentElement.style.overflowY = "hidden"
                        // 禁止body纵向滚动
                        document.body.style.height = "100%"
                        document.body.style.overflowY = "hidden"
                        // 获取header属性
                        const HeaderNavbar = document.querySelector(".header-navbar")
                        let HeaderNavbarHeight = 0
                        if(HeaderNavbar){
                            HeaderNavbarHeight = HeaderNavbar.offsetHeight + 30 || 0
                        }

                        // 修改根容器高度
                        const ContentWrapper = document.querySelector(".content-wrapper")
                        ContentWrapper.style.minHeight = "0";
                        ContentWrapper.style.height = window.innerHeight - HeaderNavbarHeight + "px";
                        ContentWrapper.style.paddingTop = "0";
                        ContentWrapper.style.marginTop = HeaderNavbarHeight + "px";
                        ContentWrapper.style.display = "flex";
                        ContentWrapper.style.flexDirection = "column";

                        // 获取根容器头部属性
                        const ContentHeader = document.querySelector(".content-wrapper .content-header")
                        let ContentHeaderHeight = 0
                        if(ContentHeader){
                            ContentHeader.style.flex = "0 0 auto";
                            ContentHeaderHeight = ContentHeader.offsetHeight || 0
                        }

                        // 修改根容器body高度
                        const ContentBody = document.querySelector(".content-wrapper .content-body")
                        ContentBody.style.flex = "1";

                        const ContentBodyRow = document.querySelector(".content-wrapper .content-body .row")
                        ContentBodyRow.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight + "px";

                        // 表格父容器
                        const DcatBox = document.querySelector(".dcat-box")
                        DcatBox.style.height = "100%";
                        DcatBox.style.display = "flex";
                        DcatBox.style.flexDirection = "column";
                        Array.from(DcatBox.children).forEach(child => {
                            // 检查子元素及其后代是否包含表格
                            if(child.className.includes("table-wrapper")) {
                                child.style.flex = "1";
                                child.style.overflowY = "auto";
                            }else{
                                // 添加flex约束
                                child.style.flex = "0 0 auto";
                                // 添加防止收缩的保险措施
                                child.style.flexShrink = "0";
                            }
                        });
                    }
                })();
                ';
                Admin::script($script);
            }
        });

	}

	public function settingForm()
	{
		return new Setting($this);
	}
}
