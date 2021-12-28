<?php

interface IModel
{
    public function save(...$args);
    public function getAll();
    public function get($id);
    public function delete($id);
    public function update();
}