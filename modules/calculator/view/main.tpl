<div class="main">
    <h2>КАЛЬКУЛЯТОР</h2>
    <form method="post">
        <div class="enter_block">
            <label for="num1">Введите первое число</label><br>
            <input id="num1" class = "numbers" type="text" name="num1" value="<?php if (isset($_POST['num1'])) echo htmlspecialchars($_POST['num1']); ?>">
        </div>

        <div  class="enter_block">
            <label for="num2">Введите второе число</label><br>
            <input id="num2" class = "numbers" type="text" name="num2" value="<?php if (isset($_POST['num2'])) echo htmlspecialchars($_POST['num2']); ?>">
        </div>
        <div class="clear"></div>
        <div class="enter_block">
            <span>Выберите действие</span>
            <ul>
                <li>
                    <input type="radio" id="f-option" name="operation" value='+' <?php if ($op == '+') echo 'checked';?>>
                    <label for="f-option">+</label>
                    <div class="check"></div>
                </li>

                <li>
                    <input type="radio" id="s-option" name="operation" value='-' <?php if ($op == '-') echo 'checked';?>>
                    <label for="s-option">-</label>
                    <div class="check"><div class="inside"></div></div>
                </li>

                <li>
                    <input type="radio" id="t-option" name="operation" value='*' <?php if ($op == '*') echo 'checked';?>>
                    <label for="t-option">*</label>
                    <div class="check"><div class="inside"></div></div>
                </li>

                <li>
                    <input type="radio" id="d-option" name="operation" value='/' <?php if ($op == '/') echo 'checked';?>>
                    <label for="d-option">/</label>
                    <div class="check"><div class="inside"></div></div>
                </li>
            </ul>
        </div>
        <input type="reset" id="clear" class="enter_block submit" value="СБРОС">
        <input class="enter_block submit" type="submit" name="submit" value="=">
    </form>

    <div class="clear"></div>
    <?php
    if (isset($_POST) && !empty($_POST)) { ?>
        <div class="result">
            <?php echo $res_string;
            ?>
        </div>
    <?php }
    ?>
</div>