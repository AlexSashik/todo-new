<div class="main container">
    <div class="container-fluid table-header">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-left">
                <span class="text-primary">Список авторов (<?php echo $res->num_rows;?>)</span>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 text-right">
                <a href="/admin/authors/add" class="btn btn-success btn-adapt">
                    <i class="glyphicon glyphicon-plus-sign"></i> Добавить
                </a>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <form method="post">
            <table class="table table-striped table-bordered">
                <thead class="navbar-inverse">
                <tr class="text-silver">
                    <th class="text-center">Имя</th>
                    <th class="text-center">Год рождения</th>
                    <th class="text-center">Год смерти</th>
                    <th class="text-center">Действие</th>
                </tr>

                <tr class="info">
                    <td>
                        <input class="form-control" type="text" name="name" <?php if (isset($_POST['name'])) echo 'value="'.htmlspecialchars($_POST['name']).'"';?>>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="yob" <?php if (isset($_POST['yob'])) echo 'value="'.htmlspecialchars($_POST['yob']).'"';?>>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="yod" <?php if (isset($_POST['yod'])) echo 'value="'.htmlspecialchars($_POST['yod']).'"';?>>
                    </td>
                    <td class="text-right">
                        <button type="submit" class="btn btn-info btn-adapt" value="submit" name="submit">
                            <i class="glyphicon glyphicon-search"></i>
                            Поиск
                        </button>
                        <a class="btn btn-info btn-adapt" href="/admin/authors" title="Reload" data-toggle="tooltip">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                    </td>
                </tr>
                </thead>


                <?php
                if (!isset($j)) {
                    $j = 1;
                    while ($row = $res->fetch_assoc()) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['name']);?></td>
                            <td>
                                <?php
                                if ($row['yob'] != 0 ) echo htmlspecialchars($row['yob']);
                                else echo '';
                                ?>
                            </td>
                            <td>
                                <?php
                                if ($row['yod'] != 0 ) echo htmlspecialchars($row['yod']);
                                else echo '';
                                ?></td>
                            </td>

                            <td class="text-center">
                                <a class="btn btn-success btn-adapt" href="/admin/authors/edit/<?php echo (int)$row['id'];?>" title="Редактировать" data-toggle="tooltip">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-adapt" onclick="return del();" href="/admin/authors?action=delete&id=<?php echo (int)$row['id'];?>" title="Удалить" data-toggle="tooltip">
                                    <i class="glyphicon glyphicon-minus"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        $j++;
                    }
                }
                ?>
            </table>
        </form>
        <?php if ($j == 1) echo '<div class="text-center not-found">Поиск не дал результата</div>';?>
    </div>
</div>

<?php if (isset($info_name)) { ?>
    <div id="modal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-<?php if($info_type == 'success') echo 'success'; else echo 'warning';?> panel-heading">
                    <button class="close" data-dismiss="modal">x</button>
                    <h4 class="modal-title text-center"><?php echo $info_name;?></h4>
                </div>
                <div class="modal-body"><?php echo $info_text;?></div>
                <div class="modal-footer">
                    <button class="btn btn-<?php if($info_type == 'success') echo 'success'; else echo 'warning';?>" data-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#modal').modal();
    </script>
    <?php
}
?>