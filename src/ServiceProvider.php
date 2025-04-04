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
            $table_header = static::setting('table_header') ?: 'close';
            $table_first = static::setting('table_first') ?: 'close';
            $table_last = static::setting('table_last') ?: 'close';
            $table_border = static::setting('table_border') ?: 'close';
            $table_zebra = static::setting('table_zebra') ?: 'close';
            $loginPath = admin_base_path('auth/login') ;
            $loginPath = ($loginPath !== '/') ? trim ($loginPath,'/' ) : $loginPath;
            // 判断是否非登录页
            $isNotLoginPage = !Helper::matchRequestPath('get:'.$loginPath) && !Helper::matchRequestPath('post:'.$loginPath);
            if ($isNotLoginPage){
                if($table_header == "close" && $table_first == "close" && $table_last == "close" && $table_border == "close" && $table_zebra == "close"){
                    return;
                }
                // 这里添加非登录页需要执行的逻辑
                echo '<script>
                  console.log(
                      "%cDcat-admin table sticky.%c / %cDcat-admin 固定表格头/列%c 🚀",
                      "background: linear-gradient(45deg, #00b4d8, #0077b6);" +
                      "color: white;" +
                      "padding: 8px 12px;" +
                      "border-radius: 8px 0 0 8px;" +
                      "font-size: 14px;" +
                      "font-weight: bold;" +
                      "text-shadow: 1px 1px 2px rgba(0,0,0,0.3);" +
                      "box-shadow: 2px 2px 4px rgba(0,0,0,0.2);",

                      "background: linear-gradient(45deg, #ff6b6b, #ff8e8e);" +
                      "color: white;" +
                      "padding: 8px 4px;" +
                      "margin: 0 -2px;" +
                      "font-size: 14px;" +
                      "transform: skewX(-10deg);",

                      "background: linear-gradient(45deg, #52b788, #40916c);" +
                      "color: white;" +
                      "padding: 8px 12px;" +
                      "border-radius: 0 8px 8px 0;" +
                      "font-size: 14px;" +
                      "font-weight: bold;" +
                      "text-shadow: 1px 1px 2px rgba(0,0,0,0.3);" +
                      "box-shadow: 2px 2px 4px rgba(0,0,0,0.2);",

                      "color: #ff6b6b;" +
                      "font-size: 20px;" +
                      "text-shadow: 0 0 8px rgba(255,107,107,0.5);"
                    );
                </script>';
                // 示例：在非登录页注入脚本
                $script = '
                ;(function() {
                    class ModifyTableStyle {
                        constructor(){
                            // 滚动容器-横向
                            this.scrollElementX = null;

                            // 滚动容器-纵向
                            this.scrollElementY = null;

                            // 表格head
                            this.theadElement = null;

                            // 表格head中的th
                            this.thElement = null;

                            // 表格body
                            this.tbodyElement = null;

                            // 表格body的tr
                            this.trElement = null;
                        }

                        init(){
                            if(this.Config("header") || this.Config("first") || this.Config("last")){
                                if(document.getElementsByTagName("table").length) {

                                    // 固定表头
                                    if(this.Config("header")){
                                        this.StickyHeader();
                                    };

                                    // 固定首列
                                    if(this.Config("first")){
                                        this.StickyFirst();
                                    };

                                    // 固定尾列
                                    if(this.Config("last")){
                                        this.StickyLast();
                                    };

                                    // 开启边框
                                    if(this.Config("border")){
                                        this.OpenBorders();
                                    };

                                    // 开启斑马纹
                                    if(this.Config("zebra")){
                                        this.OpenZebra();
                                    };
                                };
                            };
                        }

                        // 表头固定
                        StickyHeader(){
                            // 禁止html根结点纵向滚动
                            document.documentElement.style.overflowY = "hidden";
                            // 禁止body纵向滚动
                            document.body.style.height = "100%";
                            document.body.style.overflowY = "hidden";

                            // 获取header属性
                            const HeaderNavbar = document.querySelector(".header-navbar");
                            let HeaderNavbarHeight = 0;
                            if(HeaderNavbar){
                                HeaderNavbarHeight = HeaderNavbar.offsetHeight + 30 || 0;
                                // 修复顶部header中的icon在部分屏幕会竖向布局
                                const HeaderNavbarRight = HeaderNavbar.querySelector(".navbar-right");
                                if(HeaderNavbarRight?.children?.length){
                                    Array.from(HeaderNavbarRight.children).forEach(HeaderNavbarRightNav => {
                                        HeaderNavbarRightNav.style.flexDirection = "row";
                                        const HeaderNavbarRightNavMenu = HeaderNavbarRightNav.querySelector(".dropdown-menu");
                                        if(HeaderNavbarRightNavMenu){
                                            HeaderNavbarRightNavMenu.style.position = "absolute"
                                            HeaderNavbarRightNavMenu.style.top = HeaderNavbar.offsetHeight + "px"
                                        }
                                    })
                                }
                            }
                            // 获取面包屑属性
                            const IframeTabContainer = document.querySelector(".iframe-tab-container");
                            let IframeTabContainerHeight = 0;
                            if(IframeTabContainer){
                                IframeTabContainerHeight = IframeTabContainer.offsetHeight + 20 || 0;
                            }

                            // 获取根容器头部属性
                            const ContentHeader = document.querySelector(".content-header");
                            let ContentHeaderHeight = 0;
                            if(ContentHeader){
                                ContentHeader.style.flex = "0 0 auto";
                                ContentHeaderHeight = ContentHeader.offsetHeight || 0;

                                // 获取直接父元素
                                const ContentWrapper = ContentHeader.parentElement;
                                // 修改根容器高度
                                ContentWrapper.style.minHeight = "0";
                                ContentWrapper.style.height = window.innerHeight - HeaderNavbarHeight - IframeTabContainerHeight + "px";
                                ContentWrapper.style.paddingTop = "0";
                                ContentWrapper.style.marginTop = HeaderNavbarHeight + IframeTabContainerHeight + "px";
                                ContentWrapper.style.display = "flex";
                                ContentWrapper.style.flexDirection = "column";
                            }

                            // 修改根容器body高度
                            const ContentBody = document.querySelector(".content-body");
                            ContentBody.style.flex = "1";

                            const ContentBodyRow = document.querySelectorAll(".content-body .row");
                            Array.from(ContentBodyRow).forEach(child => {
                                child.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight - IframeTabContainerHeight + "px";
                                // 处理表格上有nav的情况
                                const NavTabs = document.querySelector(".nav-tabs");
                                if(NavTabs){
                                    Array.from(child.children).forEach(children => {
                                        // 修改容器高度
                                        children.style.height = window.innerHeight - HeaderNavbarHeight - ContentHeaderHeight - IframeTabContainerHeight - NavTabs.offsetHeight -20 + "px";
                                    });
                                };
                            });

                            const DcatBox = document.querySelector(".dcat-box");
                            if(DcatBox){
                                DcatBox.style.height = "100%";
                                DcatBox.style.display = "flex";
                                DcatBox.style.flexDirection = "column";
                                Array.from(DcatBox.children).forEach(child => {
                                    if(!child.className.includes("modal")){
                                        // 检查子元素及其后代是否包含表格
                                        if(child.className.includes("table-wrapper")) {
                                            child.style.flex = "1";
                                            child.style.overflowY = "auto";
                                            this.scrollElementY = child;
                                        }else{
                                            if(!child.className.includes("hidden")){
                                                // 添加flex约束
                                                child.style.flex = "0 0 auto";
                                                // 添加防止收缩的保险措施
                                                child.style.flexShrink = "0";
                                            };
                                        }
                                    }
                                });

                                // 获取table元素
                                const table = document.querySelector(".dcat-box .table")
                                if(table){
                                    // 表头
                                    this.theadElement = this.theadElement ? this.theadElement : document.getElementsByTagName("thead")[0]
                                    // console.log('.$table_header.')
                                    this.theadElement.style.position = "sticky";
                                    this.theadElement.style.top = "0";
                                    this.theadElement.style.zIndex = "99";

                                    const handleScroll = (e) => {
                                        if (e.target.scrollTop > 0) {
                                            this.theadElement.style.background = "#fff";
                                        } else {
                                            this.theadElement.style.background = "";
                                        }
                                    };

                                    // 监听&&移除监听
                                    this.ElementEventListenerY(handleScroll);
                                }
                            }
                        }

                        // 首列固定
                        StickyFirst(){
                            this.scrollElementX = this.scrollElementX ? this.scrollElementX : document.querySelector(".dcat-box .table-wrapper")
                            if(this.scrollElementX){
                                this.theadElement = this.theadElement ? this.theadElement : document.getElementsByTagName("thead")[0];
                                this.thElement = this.thElement ? this.thElement : this.theadElement.querySelectorAll("th");
                                if(this.scrollElementX.scrollLeft > 0){
                                    if(this.theadElement){
                                        this.SetStickyTheadFirstStyle(this.thElement, true)
                                    }

                                    this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                                    if(this.tbodyElement){
                                        this.tbodyElementModifyStyle((tr) => {
                                            this.SetStickyTheadFirstStyle(tr.children, true)
                                        })
                                    }
                                }
                                const handleScroll = (e) => {
                                    const scrollData = e.target

                                    if(scrollData.scrollLeft === 0){
                                        this.SetStickyTheadFirstStyle(this.thElement, false)
                                        this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                                        if(this.tbodyElement){
                                            this.tbodyElementModifyStyle((tr) => {
                                                this.SetStickyTheadFirstStyle(tr.children, false)
                                            })
                                        }
                                    }else{
                                        this.SetStickyTheadFirstStyle(this.thElement, true)
                                        this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                                        if(this.tbodyElement){
                                            this.tbodyElementModifyStyle((tr) => {
                                                this.SetStickyTheadFirstStyle(tr.children, true)
                                            })
                                        }
                                    }
                                };

                                // 监听&&移除监听
                                this.ElementEventListenerX(handleScroll);
                            }
                        }
                        // 尾列固定
                        StickyLast(){
                            this.scrollElementX = this.scrollElementX ? this.scrollElementX : document.querySelector(".dcat-box .table-wrapper")
                            if(this.scrollElementX){
                                let lastThElement = null
                                if(this.scrollElementX.offsetWidth < this.scrollElementX.scrollWidth){
                                    this.theadElement = this.theadElement ? this.theadElement : document.getElementsByTagName("thead")[0]
                                    if(this.theadElement){
                                        this.thElement = this.thElement ? this.thElement : this.theadElement.querySelectorAll("th")
                                        lastThElement = this.thElement[this.thElement.length - 1]
                                        this.SetStickyTheadLastStyle(lastThElement, true)
                                    }

                                    this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                                    if(this.tbodyElement){
                                        this.tbodyElementModifyStyle((tr) => {
                                            const lastTdElement = tr.children[tr.children.length - 1]
                                            lastTdElement.style.position = "sticky";
                                            lastTdElement.style.right = "0";
                                            lastTdElement.style.zIndex = "0";
                                            lastTdElement.style.boxShadow = "rgba(0, 0, 0, 0.12) -10px 0px 10px 0px";
                                            lastTdElement.style.background = "#fff";
                                            const menu = lastTdElement.querySelector("td .dropdown-menu");
                                            if(menu){
                                                // 创建动态样式表
                                                const styleId = "menu-right";
                                                if(!document.getElementById(styleId)) {
                                                    const style = document.createElement("style");
                                                    style.id = styleId;
                                                    style.innerHTML = `
                                                        .right{
                                                            right: ${lastTdElement.offsetWidth + "px"} !important;
                                                        }
                                                        .right::before{
                                                            left: unset !important;
                                                            right: 0 !important;
                                                        }
                                                    `;
                                                    document.head.appendChild(style);
                                                }
                                                menu.classList.add("right");
                                            }
                                        })
                                    }

                                    const handleScroll = (e) => {
                                        const scrollData = e.target

                                        if(scrollData.scrollWidth - scrollData.clientWidth === scrollData.scrollLeft){
                                            this.SetStickyTheadLastStyle(lastThElement, false)
                                            this.SetStickyTbodyLastStyle(false)
                                        }else{
                                            this.SetStickyTheadLastStyle(lastThElement, true)
                                            this.SetStickyTbodyLastStyle(true)
                                        }
                                    };

                                    // 监听&&移除监听
                                    this.ElementEventListenerX(handleScroll);
                                }
                            }
                        }

                        // 设置首列固定表头样式
                        SetStickyTheadFirstStyle(thElement, flag){
                            const setStyle = (Element) => {
                                Element.style.position = flag ? "sticky" : "unset";
                                Element.style.zIndex = "999";
                                Element.style.left = "0";
                                Element.style.background = flag ? "#fff" : "unset";
                            }
                            const checkBox = thElement[0].querySelector("input");
                            if(checkBox?.type === "checkbox"){
                                [0, 1].forEach(item => {
                                    setStyle(thElement[item])
                                    if(item == 1){
                                        thElement[item].style.left = thElement[0].offsetWidth + "px";
                                        thElement[item].style.boxShadow = flag ? "rgba(0, 0, 0, 0.12) 10px 2px 10px -5px" : "unset";
                                    }
                                })
                            }else{
                                setStyle(this.thElement[0])
                                thElement[0].style.boxShadow = flag ? "rgba(0, 0, 0, 0.12) 10px 2px 10px -5px" : "unset";
                            }
                        }

                        SetStickyTbodyFirstStyle(flag=true){

                        }

                        // 设置尾列固定表头样式
                        SetStickyTheadLastStyle(Element, flag){
                            Element.style.position = flag ? "sticky" : "unset";
                            Element.style.zIndex = "999";
                            Element.style.right = "0";
                            Element.style.boxShadow = flag ? "rgba(0, 0, 0, 0.12) -10px 0px 10px 0px" : "unset";
                            Element.style.background = flag ? "#fff" : "unset";
                        }

                        // 设置尾列固定内容样式
                        SetStickyTbodyLastStyle(flag=true){
                            this.tbodyElementModifyStyle((tr) => {
                                const lastTdElement = tr.children[tr.children.length - 1]
                                lastTdElement.style.position = flag ? "sticky" : "unset";
                                lastTdElement.style.boxShadow = flag ? "rgba(0, 0, 0, 0.12) -10px 0px 10px 0px" : "unset";
                                lastTdElement.style.background = flag ? "#fff" : "unset";
                            })
                        }

                        // 开启斑马纹
                        OpenZebra(){
                            this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                            // 创建动态样式表
                            const styleId = "zebra-hover-style";
                            if(!document.getElementById(styleId)) {
                                const style = document.createElement("style");
                                style.id = styleId;
                                style.innerHTML = `
                                    .zebra-row:hover {
                                        background: #f5f7fa !important;
                                    }
                                `;
                                document.head.appendChild(style);
                            }

                            this.tbodyElementModifyStyle((child, index) => {
                                // 修改hover背景色
                                // transform: scale(1.02);
                                // transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                                child.classList.add("zebra-row");
                                // 偶数行修改背景色
                                if(index % 2 === 1){
                                    child.style.background = "#fafafa";
                                }
                            })
                        }

                        // 开启边框
                        OpenBorders(){
                            this.tbodyElement = this.tbodyElement ? this.tbodyElement : document.getElementsByTagName("tbody")[0]
                            this.tbodyElementModifyStyle((tr) => {
                                Array.from(tr.children).forEach((td, index) => {
                                    td.style.borderRight = "1px solid #ebeef5"
                                    td.style.borderBottom = "1px solid #ebeef5"
                                })
                            })

                            // this.theadElement = this.theadElement ? this.theadElement : document.getElementsByTagName("thead")[0]
                            // if(this.theadElement){
                            //     this.thElement = this.thElement ? this.thElement : this.theadElement.querySelectorAll("th")
                            //     Array.from(this.thElement).forEach((th, index) => {
                            //        th.style.borderRight = "1px solid #000"
                            //        th.style.borderBottom = "1px solid #000"
                            //     })
                            // }
                        }

                        tbodyElementModifyStyle(callback){
                            Array.from(this.tbodyElement.children).forEach((child, index) => {
                                callback(child, index)
                            })
                        }

                        ElementEventListenerY(callback){
                            // 移除旧的事件监听器
                            this.scrollElementY.removeEventListener("scroll", callback);
                            // 添加新的事件监听器
                            this.scrollElementY.addEventListener("scroll", callback);
                        }

                        ElementEventListenerX(callback){
                            // 移除旧的事件监听器
                            this.scrollElementX.removeEventListener("scroll", callback);
                            // 添加新的事件监听器
                            this.scrollElementX.addEventListener("scroll", callback);
                        }

                        Config(variable){
                            switch (variable) {
                                case "header":
                                    return '.json_encode($table_header).' == "open";
                                    break;
                                case "first":
                                    return '.json_encode($table_first).' == "open";
                                    break;
                                case "last":
                                    return '.json_encode($table_last).' == "open";
                                    break;
                                case "border":
                                    return '.json_encode($table_border).' == "open";
                                    break;
                                default:
                                    return '.json_encode($table_zebra).' == "open";
                                    break;
                            }
                        }
                    }

                    const ModifyTableStyleObj = new ModifyTableStyle();
                    ModifyTableStyleObj.init();
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
