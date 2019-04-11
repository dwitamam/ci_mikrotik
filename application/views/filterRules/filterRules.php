<div class="page-header">
    <h1>Filter Rules</h1>
    <hr>
    <?php
        $flashmessage = $this->session->flashdata('message');
        echo !empty($flashmessage) ? '<div class="alert alert-success alert-dismissible" role="alert">'. $flashmessage. '</div>': '';
    ?>
    <p><?php echo !empty($total_result) ? "Terdapat $total_result data filter rules" : ""; ?></p>

    <?php echo !empty($table)? $table : ''; ?>

    <p>
        <?php
            if(!empty($link)){
                foreach($link as $row){
                    echo $row;
                }
            } 
        ?>  
    </p>
</div>