<?php

class StockController extends BaseController {
    public function stock() {
        $this->view('stock-products/viewStock');
    }
}