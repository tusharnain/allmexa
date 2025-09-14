<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>
        <?= lang('Errors.pageNotFound') ?>
    </title>

    <style>
        * {
            transition: all 0.6s;
        }

        html {
            height: 100%;
        }

        body {
            font-family: 'Lato', sans-serif;
            color: #888;
            margin: 0;
        }

        #main {
            display: table;
            width: 100%;
            height: 100vh;
            text-align: center;
        }

        .fof {
            display: table-cell;
            vertical-align: middle;
        }

        .fof h1 {
            font-size: 50px;
            display: inline-block;
            padding-right: 12px;
            animation: type .5s alternate infinite;
            margin: 0;
        }

        .fof p {
            font-size: 20px;
            padding-right: 12px;
            animation: type .5s alternate infinite;
        }

        @keyframes type {
            from {
                box-shadow: inset -3px 0px 0px #888;
            }

            to {
                box-shadow: inset -3px 0px 0px transparent;
            }
        }
    </style>
</head>

<body>
    <div id="main">
        <div class="fof">
            <h1>404!</h1>
            <p>
                <?php if (isProduction()): ?>
                    <?= lang('Errors.sorryCannotFind') ?>
                <?php else: ?>
                    <?= nl2br(esc($message)) ?>
                <?php endif; ?>
            </p>
        </div>
    </div>
</body>

</html>