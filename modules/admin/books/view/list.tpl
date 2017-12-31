<div class="main container">
    <div class="container-fluid table-header">
        <div class="row">
            <div class="col-xs-7 col-sm-6 col-md-6 col-lg-6 text-left">
                <span class="text-primary">Список книг (<?php echo $res->num_rows;?>)</span>
            </div>
            <div class="col-xs-5 col-sm-6 col-md-6 col-lg-6 text-right">
                <a href="/admin/books/add" class="btn btn-success btn-adapt">
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
                    <th class="text-center">Название</th>
                    <th class="text-center">Авторы</th>
                    <th class="text-center">Год</th>
                    <th class="text-center">Действие</th>
                </tr>
                <tr class="info">
                    <td>
                        <input class="form-control" type="text" name="name" <?php if (isset($_POST['name'])) echo 'value="'.htmlspecialchars($_POST['name']).'"';?>>
                    </td>
                    <td>
                        <input class="form-control" type="text" name="auth" <?php if (isset($_POST['auth'])) echo 'value="'.htmlspecialchars($_POST['auth']).'"';?>>
                    </td>
                    <td>
                        <select class="form-control" name="year[]">
                            <option value="all"></option>
                            <?php
                            if (isset($_POST['year'][0])) {
                                for ($i = 1900; $i <= (int)date("Y"); $i++) {
                                    if ($_POST['year'][0] == $i) {
                                        echo '<option selected value="'.$i.'">'.$i.'</option>';
                                    } else {
                                        echo '<option value="'.$i.'">'.$i.'</option>';
                                    }
                                }
                            } else {
                                for ($i = 1900; $i <= (int)date("Y"); $i++) {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            }
                            ?>
                        </select>
                    </td>
                    <td class="text-right">
                        <button type="submit" class="btn btn-info btn-adapt" value="submit" name="submit">
                            <i class="glyphicon glyphicon-search"></i>
                            Поиск
                        </button>
                        <a class="btn btn-info btn-adapt" href="/admin/books" title="Reload" data-toggle="tooltip">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                    </td>
                </tr>
                </thead>

                <?php
                $i = 1;
                if(isset($data)) {
                    foreach ($data as $v) {
                        ?>
                        <tr>
                            <td><?php echo htmlspecialchars($v['name']);?></td>
                            <td>
                                <?php
                                foreach ($v['auth'] as $author) {
                                    echo htmlspecialchars($author).'<br>';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($v['year']);?></td>
                            <td class="text-center">
                                <a class="btn btn-success btn-adapt" href="/admin/books/edit/<?php echo (int)$v['id'];?>" title="Редактировать" data-toggle="tooltip">
                                    <i class="glyphicon glyphicon-edit"></i>
                                </a>
                                <a class="btn btn-danger btn-adapt" onclick="return del();" href="/admin/books?action=delete&id=<?php echo (int)$v['id'];?>" title="Удалить" data-toggle="tooltip">
                                    <i class="glyphicon glyphicon-minus"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        $i++;
                    }
                }
                ?>
            </table>
        </form>
        <?php if ($i == 1) echo '<div class="text-center not-found">Поиск не дал результата</div>';?>
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