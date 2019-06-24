<div class="page-header">
    <h1>Bandwidth</h1><?php echo "TX: <kbd>".$nyom2."</kbd> "." | RX: <kbd>".$nyom1."</kbd><br>"."RATA - RATA TX MAX: <kbd>".$nyom4."</kbd> | RATA - RATA RX MAX: <kbd>".$nyom3."</kbd><br>TX MAX TOTAL: <kbd>".$nyom6."</kbd> | TX MAX TOTAL: <kbd>".$nyom5."</kbd>"; ?>
    <hr>
    <?php
        $flashmessage = $this->session->flashdata('message');
        echo !empty($flashmessage) ? '<div class="alert alert-success alert-dismissible" role="alert">'. $flashmessage. '</div>': '';
    ?>
    <p><?php echo !empty($total_result) ? "Terdapat $total_result data mangle" : ""; ?></p>

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