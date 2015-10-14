<?php
class NotFound extends Page_Template {
    public function make($p_query) {
        $this->Header_Page("404 - ФТИ КФУ им. В. И. Вернадского");
        echo "<h1>404 &mdash; запрашиваемый документ не найден</h1>";
        $this->Footer_Page();
    }
}
?>