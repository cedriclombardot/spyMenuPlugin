<?php
use_stylesheet('/css/treeMenu.css');
use_javascript('/js/tree.js');
echo $sf_data->getRaw('sfMenu');
?>
<script type="text/javascript">make_tree_menu('listMenuRootTree',1)</script>