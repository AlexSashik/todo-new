window.onload = function () {
    document.addEventListener('keydown', changeDirection);
    setInterval(loop, 100/60);

    var
        canv                = document.getElementById('mc'),
        ctx                 = canv.getContext('2d'),
        fs = fkp            = false, // game started and first kay pressed respectively
        speed = basespeed   = 1, // snake movement speed
        xv = yv             = 0, // velocities (x and y)
        px                  = ~~(canv.width) / 2, // player X position
        py                  = ~~(canv.height) / 2, // player Y position
        pw = ph             = 20, // player size
        aw = ah             = 20, // apple size
        apples              = [], // apples list
        trail               = [], // tail element list (aka trail)
        tail                = 200, // tail size (1 for 10)
        tailSafeZone        = 40, // self eating protection for head zone (aka safeZone)
        cooldown            = false, // is key in cooldown mode
        score               = 0; // current score



    // game main loop
    function loop() {
        // logic
        ctx.fillStyle = 'black';
        ctx.fillRect(0,0, canv.width, canv.height);

        // force speed
        px += xv;
        py += yv;

        // boundary conditions (teleport)
        if (px > canv.width) {
            px = 0;
        }
        if (px + pw < 0) {
            px = canv.width;
        }
        if (py + ph < 0) {
            py = canv.height;
        }
        if (py > canv.height) {
            py = 0;
        }

        ctx.fillStyle = 'lime';
        for (var i = 0; i < trail.length; i++)  {
            ctx.fillStyle = trail[i].color || 'lime';
            ctx.fillRect(trail[i].x, trail[i].y, pw, ph);
        }
        trail.push({x: px, y: py, color: ctx.fillStyle});

        // limiter
        if (trail.length > tail) {
            trail.shift();
        }

        // eaten
        if (trail.length > tail) {
            trail.shift();
        }


        // self collisions
        if (trail.length >= tail && fs) {
            for(var i = trail.length - tailSafeZone; i >= 0; i--) {
                if (
                    trail[i].x < px + pw
                    && trail[i].x > px - pw
                    && trail[i].y < py + ph
                    && trail[i].y > py - ph
                ) {
                    // got collision
                    tail = 50; // cut the tail
                    speed = basespeed; // cut the speed
                    for (var t = 0; t < trail.length; t++) {
                        trail[t].color = 'red';
                        if (t >= trail.length - tail) {
                            break;
                        }
                    }
                }
            }
        }

       // paint new apples
        for(var a = 0; a < apples.length; a++) {
            ctx.fillStyle = apples[a].color;
            ctx.fillRect(apples[a].x, apples[a].y, aw, ah);
        }

        // check for snake head collisions with apples
        for (var a = 0; a < apples.length; a++ ) {
            if (
                px < apples[a].x + pw
                && px + pw > apples[a].x
                && py < (apples[a].y + ph)
                && ph + py > apples[a].y
            ) {
                // got collision with apple
                apples.splice(a, 1);
                a += 10; // add tail lenght
                speed += 0.01; // add some speed
                spawnApple(); // spawn another apple(-s)
                tail += 10;
                break;

            }
        }
    }

    // apples spawner
    function spawnApple() {
        var
            newApple = {
                x: ~~(Math.random() * canv.width),
                y: ~~(Math.random() * canv.height),
                color: 'red'
            };

        if (
            (newApple.x < aw || newApple.x > canv.width - aw)
            ||
            (newApple.y < ah || newApple.y > canv.height - ah)
        ) {
            spawnApple();
            return;
        }

        for (var i = 0; i < tail.length; i++) {
            if (
                newApple.x < trail[i].x + aw
                && newApple.x + aw > trail[i].x
                && newApple.y < (trail[i].y + ah)
                && ah + newApple.y > trail[i].y
            ) {
                spawnApple();
                return;
            }
        }

        apples.push(newApple);

        if(apples.length < 3 && ~~(Math.random() * 1000) > 700) {
            spawnApple();
        }
    }

    // random color generator in hex
    function rc() {
        return '#' + ((~~(Math.random()*255)).toString(16)) +
            ((~~(Math.random()*255)).toString(16)) + ((~~(Math.random()*255)).toString(16));
    }
    
    // velocity changer (controls)
    function changeDirection(evt) {
        if(!fkp && [65,87,68,83].indexOf(evt.keyCode) > -1) {
            setTimeout(function () {
                fs = true;
            }, 1000);
            fkp = true;
            spawnApple();
        }
        if (cooldown) {
            return false;
        }

        // обрабатываем нажатие стрелок влево-вверх-вправо-вниз (коды кнопок 37-38-39-40)
        if(evt.keyCode == 65 && !(xv > 0)) {
            xv = -speed;
            yv = 0;
        }

        if(evt.keyCode == 87 && !(yv > 0)) {
            xv = 0;
            yv = -speed;
        }

        if(evt.keyCode == 68 && !(xv < 0)) {
            xv = speed;
            yv = 0;
        }

        if(evt.keyCode == 83 && !(yv < 0)) {
            xv = 0;
            yv = speed;
        }

        cooldown = true;
        setTimeout(function () {
            cooldown = false;
        }, 100)

    }
};