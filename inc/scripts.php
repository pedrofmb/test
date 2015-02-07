<!-- Bootstrap core JavaScript
    ================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<!--script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script-->
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/fechas.js"></script>

<?php if(isset($scripts)): ?>
    <?php foreach ($scripts as $script): ?>
    <script type="text/javascript" src="js/<?php echo $script; ?>"></script>
    <?php endforeach; ?>
<?php endif; ?>