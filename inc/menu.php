<!-- Static navbar -->
<div class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><?php echo PAGE_TITLE; ?></a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!--li class="dropdown <?php if($modulo=="inicio"){ echo "active";}?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon glyphicon-list-alt"></i> Inicio<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="mantenimiento.php?option=banners">Banners</a></li>
                        <li><a href="mantenimiento.php?option=ofertas">Ofertas</a></li>
                    </ul>
                </li-->
                
                <li class="dropdown <?php if($modulo=="productos"){ echo "active";}?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon-cog"></i> Productos<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <li><a href="producto.php?option=galeria">Galer&iacute;a</a></li>  
                    </ul>
                </li>
                
                <li class="dropdown <?php if($modulo=="mantenimiento"){ echo "active";}?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="glyphicon glyphicon glyphicon-list"></i> Mantenimiento<b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!--li><a href="#">Contacto</a></li-->
                        <li><a href="mantenimiento.php?option=categoria">Categor&iacute;as (L&iacute;neas)</a></li>
                        <li><a href="mantenimiento.php?option=subcategoria">Sub-Categor&iacute;as</a></li>                        
                        <li><a href="mantenimiento.php?option=clientes">Clientes</a></li>                        
                    </ul>
                </li-->
                
                <li><a href="#"><i class="glyphicon glyphicon-question-sign"></i> Ayuda</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">                
                <li><a href="logout.php"><i class="glyphicon glyphicon-log-out"></i> Cerrar Sesi&oacute;n</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div><!--/.container-fluid -->
</div>