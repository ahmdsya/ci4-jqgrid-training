<!DOCTYPE html>

<html lang="en">
    <head>
        <?= $this->include('layouts/header') ?>

        <title>jqGrid Loading Data - JSON</title>

        <style>
            input, textarea {
                text-transform: uppercase;
                /* padding: 5px; */
            }
            .highlight { background-color: greenyellow }
            /* mark{
                background: greenyellow;
                color: black;
            } */
        </style>
    </head>

    <body>

        <?= $this->renderSection('content') ?>

        <?= $this->include('layouts/footer') ?>

        <?= $this->renderSection('script') ?>

    </body>

</html>