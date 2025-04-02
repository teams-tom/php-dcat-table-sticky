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
            $table_header = static::setting('table_header') ?: 'open';

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
                    if(document.getElementsByTagName("table").length){
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


                        const ContentBodyRow = document.querySelectorAll(".content-body .row")
                        Array.from(ContentBodyRow).forEach(child => {
                            child.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight + "px";
                            // 处理表格上有nav的情况
                            const NavTabs = document.querySelector(".nav-tabs")
                            if(NavTabs){
                                Array.from(child.children).forEach(children => {
                                    // 修改容器高度
                                    children.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight - NavTabs.offsetHeight -20 + "px";
                                })
                            }

                        })

                        // 表格父容器
                        const DcatBox = document.querySelector(".dcat-box")
                        if(DcatBox){
                            DcatBox.style.height = "100%";
                            DcatBox.style.display = "flex";
                            DcatBox.style.flexDirection = "column";
                            let scrollElement = null
                            Array.from(DcatBox.children).forEach(child => {
                                if(!child.className.includes("modal")){
                                    // 检查子元素及其后代是否包含表格
                                    if(child.className.includes("table-wrapper")) {
                                        child.style.flex = "1";
                                        child.style.overflowY = "auto";
                                        scrollElement = child
                                    }else{
                                        if(!child.className.includes("hidden")){
                                            // 添加flex约束
                                            child.style.flex = "0 0 auto";
                                            // 添加防止收缩的保险措施
                                            child.style.flexShrink = "0";
                                        }
                                    }
                                }
                            });

                            // 获取table元素
                            const table = document.querySelector(".dcat-box .table")
                            if(table){
                                // 表头
                                const theadElement = document.getElementsByTagName("thead")[0]
                                // console.log('.$table_header.')
                                theadElement.style.position = "sticky";
                                theadElement.style.top = "0";
                                theadElement.style.zIndex = "99";

                                const handleScroll = function(e) {
                                    if (e.target.scrollTop > 0) {
                                        theadElement.style.background = "#fff";
                                    } else {
                                        theadElement.style.background = "";
                                    }
                                };
                                // 移除旧的事件监听器
                                scrollElement.removeEventListener("scroll", handleScroll);
                                // 添加新的事件监听器
                                scrollElement.addEventListener("scroll", handleScroll);
                            }
                        }
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
