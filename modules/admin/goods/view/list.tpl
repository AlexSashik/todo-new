<main class="container main">
    <form method="post" id="del_form" onsubmit="return delAll();"></form>
    <div class="container-fluid table-header">
        <div class="row">
            <div class="col-xs-7 col-sm-6 col-md-6 col-lg-6 text-left">
                <span class="text-primary">Список товаров (<?php echo $res->num_rows;?>)</span>
            </div>
            <div class="col-xs-5 col-sm-6 col-md-6 col-lg-6 text-right">
                <button form="del_form" type="submit" name="delete" class="btn btn-danger btn-adapt">
                    <i class="glyphicon glyphicon-trash"></i> Удалить
                </button>
                <a href="/admin/goods/add" class="btn btn-success btn-adapt">
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
                    <th class="text-center">Выбор</th>
                    <th class="text-center">Название</th>
                    <th class="text-center">Категория</th>
                    <th class="text-center">Активен</th>
                    <th class="text-center">Цена(грн)</th>
                    <th class="text-center">Действие</th>
                </tr>

                <tr class="info">
                    <td class="text-center"><input type="checkbox" id="all_goods"></td>
                    <td>
                        <input class="form-control" type="text" name="name" <?php if (isset($_SESSION['name'])) {echo 'value="'.htmlspecialchars($_SESSION['name']).'"'; unset($_SESSION['name']);}?>>
                    </td>
                    <td>
                        <select class="form-control" name="cat[]">
                            <?php
                            $res_cat = q ("
                                    SELECT * FROM `goods_cat`
                                ");
                            if (!isset($_SESSION['search_by_cat'])) {
                                echo '<option selected value="all">Все</option>';
                                while ($row = $res_cat->fetch_assoc()) {
                                    echo '<option value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                                }
                            } else {
                                echo '<option value="all">Все</option>';
                                while ($row = $res_cat->fetch_assoc()) {
                                    if ($_SESSION['search_by_cat'] == $row['cat']) {
                                        echo '<option selected value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                                    } else {
                                        echo '<option value="'.htmlspecialchars($row['cat']).'">'.htmlspecialchars($row['cat']).'</option>';
                                    }
                                }
                                unset($_SESSION['search_by_cat']);
                            }
                            $res_cat->close();
                            ?>
                        </select>
                    </td>
                    <td>
                        <select class="form-control" name="is_in_sight[]">
                            <?php
                            if (!isset($_SESSION['is_in_sight'])) {
                                ?>
                                <option selected value="all">Все</option>
                                <option value="1">В наличии</option>
                                <option value="0">Отсутствует</option>
                                <?php
                            } else {
                                ?>
                                <option value="all">Все</option>
                                <option <?php if ($_SESSION['is_in_sight'] == 1) echo 'selected';?> value="1">В наличии</option>
                                <option <?php if ($_SESSION['is_in_sight'] == 0) echo 'selected';?> value="0">Отсутствует</option>
                                <?php
                                unset ($_SESSION['is_in_sight']);
                            }
                            ?>
                        </select>
                    </td>
                    <td>
                        <div class="form-group">
                            <div <?php if (isset($price_err)) echo 'class="has-error"';?>>
                                <input
                                    class="form-control"
                                    type="text"
                                    name="price_from"
                                    placeholder="от"
                                    <?php
                                    if (isset($_SESSION['price_from'])) {
                                        echo 'value="'.htmlspecialchars($_SESSION['price_from']).'"';
                                        unset($_SESSION['price_from']);
                                    }?>
                                >
                            </div>
                            <div <?php if (isset($price_err)) echo 'class="has-error"';?>>
                                <input
                                    class="form-control"
                                    type="text"
                                    name="price_to"
                                    placeholder="до"
                                    <?php if (isset($_SESSION['price_to'])) {
                                        echo 'value="'.htmlspecialchars($_SESSION['price_to']).'"';
                                        unset($_SESSION['price_to']);
                                    }
                                    ?>
                                >
                            </div>
                        </div>

                    </td>
                    <td class="text-right">
                        <button type="submit" class="btn btn-info btn-adapt" value="submit" name="submit">
                            <i class="glyphicon glyphicon-search"></i>
                            Поиск
                        </button>
                        <a class="btn btn-info btn-adapt" href="/admin/goods" data-toggle="tooltip" data-placement="left" title="Reload">
                            <i class="glyphicon glyphicon-refresh"></i>
                        </a>
                    </td>
                </tr>
                </thead>
                <tbody>
                <?php
                $i = 1;
                while ($row = $res->fetch_assoc() ) {
                    ?>
                    <tr>
                        <td class="text-center"><input form="del_form" type="checkbox" name="ids[]" value="<?php echo $row['id'];?>" class="goods_checkboxes"></td>
                        <td><?php echo htmlspecialchars($row['name']);?></td>
                        <td><?php echo htmlspecialchars($row['cat']);?></td>
                        <td><?php if ($row['is_in_sight']) echo 'В наличии'; else echo 'Отсутствует';?></td>
                        <td><?php echo htmlspecialchars($row['price']);?></td>
                        <td class="text-center">
                            <a class="btn btn-success btn-adapt" href="/admin/goods/edit/<?php echo (int)$row['id'];?>" title="Редактировать" data-toggle="tooltip">
                                <i class="glyphicon glyphicon-edit"></i>
                            </a>
                            <a class="btn btn-danger btn-adapt" onclick="return del();" href="/admin/goods?action=delete&id=<?php echo (int)$row['id'];?>" title="Удалить" data-toggle="tooltip">
                                <i class="glyphicon glyphicon-minus"></i>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $i++;
                }
                ?>
                </tbody>
            </table>
        </form>
        <?php if ($i == 1) echo '<div class="text-center not-found">Поиск не дал результата</div>';?>
    </div>
</main>

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