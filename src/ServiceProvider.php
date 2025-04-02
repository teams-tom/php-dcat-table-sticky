<?php

namespace TeamsTom\TableSticky;

use Dcat\Admin\Extend\ServiceProvider as BaseServiceProvider;
use Dcat\Admin\Admin;
use Dcat\Admin\Support\Helper;
use Dcat\Admin\Traits\HasFormResponse;

class ServiceProvider extends BaseServiceProvider
{
    use HasFormResponse;

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



                        // 获取根容器头部属性
                        const ContentHeader = document.querySelector(".content-header")
                        let ContentHeaderHeight = 0
                        if(ContentHeader){
                            ContentHeader.style.flex = "0 0 auto";
                            ContentHeaderHeight = ContentHeader.offsetHeight || 0;

                            // 获取直接父元素
                            const ContentWrapper = ContentHeader.parentElement;
                            // 修改根容器高度
                            ContentWrapper.style.minHeight = "0";
                            ContentWrapper.style.height = window.innerHeight - HeaderNavbarHeight + "px";
                            ContentWrapper.style.paddingTop = "0";
                            ContentWrapper.style.marginTop = HeaderNavbarHeight + "px";
                            ContentWrapper.style.display = "flex";
                            ContentWrapper.style.flexDirection = "column";
                        }


                        // 修改根容器body高度
                        const ContentBody = document.querySelector(".content-body")
                        ContentBody.style.flex = "1";


                        const ContentBodyRow = document.querySelectorAll(".row")
                        Array.from(ContentBodyRow).forEach(child => {
                            child.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight + "px";
                        })

                        // 处理表格上有nav的情况
                        const NavTabs = document.querySelector(".nav-tabs")
                        if(NavTabs){
                            // 获取直接父元素
                            const NavTabsParent = NavTabs.parentElement;
                            // 修改父容器高度
                            NavTabsParent.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight - NavTabsParent.offsetHeight + "px";
                        }

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
                                if(!child.className.includes("hidden")){
                                    // 添加flex约束
                                    child.style.flex = "0 0 auto";
                                    // 添加防止收缩的保险措施
                                    child.style.flexShrink = "0";
                                }
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
