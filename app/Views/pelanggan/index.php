<?= $this->extend('layouts/master') ?>

<?= $this->section('content') ?>

    <div style="margin:20px;">

        <input type="text" 
            id="keyword" 
            class="form-control" 
            placeholder="Global Search..."
            style="margin-bottom: 20px;"
            >

        <table id="jqGrid"></table>
        <div id="jqGridPager"></div>

        <br>
        <br>

        <table id="jqGridDetails"></table>
        <div id="jqGridDetailsPager"></div>

        <div id="Dialog"></div>
    </div>

<?= $this->endSection() ?>

<?= $this->section('script') ?>

    <script> var baseUrl = '<?= base_url() ?>' </script>
    <script src="/js/script.js"></script>
    
<?= $this->endSection() ?>