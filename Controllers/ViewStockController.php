<?php

class ViewStockController extends BaseController {
    public function stock() {
        $this->view('stock-products/viewStock');
    }
}