<?php
/**
 * Created by PhpStorm.
 * User: zzwu
 * Date: 17-1-10
 * Time: 上午9:58
 */

namespace app\admin\driver;

use think\paginator\driver\Bootstrap;


class AjaxPage extends Bootstrap
{
    /**
     * 获取总数据条数 html代码
     * @return string
     */
    protected function getTotal(){
        $html = '<li class="disabled"><span>共<strong>'.ceil($this->total()/$this->listRows()).'</strong>页</span></li>';
        return $html;
    }

    /**
     * 渲染分页html
     * @return mixed
     */
    public function render()
    {
        if ($this->hasPages()) {
            if ($this->simple) {
                return sprintf(
                    '<ul class="pager">%s %s</ul>',
                    $this->getPreviousButton(),
                    $this->getNextButton()
                );
            } else {
                return sprintf(
                    '<ul class="pagination">%s %s %s %s %s %s</ul>',
                    $this->getPreviousButton(),
                    $this->getFirstPage(),
                    $this->getLinks(),
                    $this->getLastPage(),
                    $this->getNextButton(),
                    $this->getTotal()
                );
            }
        }
    }

    /**
     * 上一页按钮
     * @param string $text
     * @return string
     */
    protected function getPreviousButton($text = "上一页")
    {
        return parent::getPreviousButton($text);
    }

    /**
     * 下一页按钮
     * @param string $text
     * @return string
     */
    protected function getNextButton($text = '下一页')
    {
        return parent::getNextButton($text);
    }

    /**
     * 尾页按钮
     * @return string
     */
    public function getLastPage()
    {
        return $this->getPageLinkWrapper($this->url($this->lastPage()), '尾页');
    }

    /**
     * 首页按钮
     * @return string
     */
    public function getFirstPage()
    {
        return $this->getPageLinkWrapper($this->url(1), '首页');
    }

    /**
     * 生成一个可点击的按钮
     *
     * @param  string $url
     * @param  int    $page
     * @return string
     */
    protected function getAvailablePageWrapper($url, $page)
    {
        return '<li><a href="javascript:void(0);" onclick="ajaxResult(\'' . htmlentities($url) . '\')">' . $page . '</a></li>';
    }
}