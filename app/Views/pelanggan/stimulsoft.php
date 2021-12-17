<!DOCTYPE html>
<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<title>Stimulsoft Reports.JS</title>
	<style>
		html,
		body {
			font-family: system-ui;
		}

	</style>

    <link href="<?= base_url()?>/stimulsoft-report/2021.3.6/css/stimulsoft.viewer.office2013.whiteblue.css" rel="stylesheet"/>
    <link href="<?= base_url()?>/stimulsoft-report/2021.3.6/css/stimulsoft.designer.office2013.whiteblue.css" rel="stylesheet"/>

    <script src="<?= base_url()?>/stimulsoft-report/2021.3.6/scripts/stimulsoft.reports.js" type="text/javascript"></script>
	<script src="<?= base_url()?>/stimulsoft-report/2021.3.6/scripts/stimulsoft.dashboards.js" type="text/javascript"></script>
    <script src="<?= base_url()?>/stimulsoft-report/2021.3.6/scripts/stimulsoft.viewer.js" type="text/javascript"></script>
    <script src="<?= base_url()?>/stimulsoft-report/2021.3.6/scripts/stimulsoft.designer.js" type="text/javascript"></script>
    <script src="<?= base_url()?>/stimulsoft-report/2021.3.6/scripts/stimulsoft.blockly.js" type="text/javascript"></script>

    <script>

        function onLoad()
        {
            // Stimulsoft.Base.StiLicense.Key = "6vJhGtLLLz2GNviWmUTrhSqnOItdDwjBylQzQcAOiHn0s4gy0Fr5YoUZ9V00Y0igCSFQzwEqYBh/N77k4f0fWXTHW5rqeBNLkaurJDenJ9o97TyqHs9HfvINK18Uwzsc/bG01Rq+x3H3Rf+g7AY92gvWmp7VA2Uxa30Q97f61siWz2dE5kdBVcCnSFzC6awE74JzDcJMj8OuxplqB1CYcpoPcOjKy1PiATlC3UsBaLEXsok1xxtRMQ283r282tkh8XQitsxtTczAJBxijuJNfziYhci2jResWXK51ygOOEbVAxmpflujkJ8oEVHkOA/CjX6bGx05pNZ6oSIu9H8deF94MyqIwcdeirCe60GbIQByQtLimfxbIZnO35X3fs/94av0ODfELqrQEpLrpU6FNeHttvlMc5UVrT4K+8lPbqR8Hq0PFWmFrbVIYSi7tAVFMMe2D1C59NWyLu3AkrD3No7YhLVh7LV0Tttr/8FrcZ8xirBPcMZCIGrRIesrHxOsZH2V8t/t0GXCnLLAWX+TNvdNXkB8cF2y9ZXf1enI064yE5dwMs2fQ0yOUG/xornE";
            Stimulsoft.Base.StiLicense.loadFromFile("<?= base_url() ?>/stimulsoft-report/2021.3.6/stimulsoft/license.php");
            
            const dataSet = new Stimulsoft.System.Data.DataSet("Data");
            dataSet.readJson(<?= json_encode($dataPelanggan)?>);

            const report = new Stimulsoft.Report.StiReport();
            report.regData(dataSet.dataSetName, "", dataSet);
            report.loadFile("<?= base_url() ?>/report/MasterDetail.mrt");

            const designer = new Stimulsoft.Designer.StiDesigner(null, "Designer", false);
            designer.report = report;
            
            const viewer = new Stimulsoft.Viewer.StiViewer(null, "StiViewer", false);
            viewer.renderHtml("content");
            // designer.renderHtml("content");
            
            viewer.report = report;
            // console.log("<?= base_url()?>");
        }
    </script>

</head>
<body onLoad="onLoad()">
    <div id="content">Component should be here</div>
</body>
</html>
