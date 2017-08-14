<?php
namespace app\admin\controller;


class Manage extends Common
{
    public function index()
    {
        return $this->fetch('manage/index');
    }
}
