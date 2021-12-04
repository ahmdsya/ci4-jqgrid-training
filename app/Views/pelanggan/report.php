<!DOCTYPE html>
<html>
<head>

  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Report Pelanggan - Pesanan</title>
  <link rel="stylesheet" type="text/css" href="/stimulsoft-report/2021.3.6/css/stimulsoft.viewer.office2013.whiteblue.css">
  <link rel="stylesheet" type="text/css" href="/stimulsoft-report/2021.3.6/css/stimulsoft.designer.office2013.whiteblue.css">
  <script type="text/javascript" src="/stimulsoft-report/2021.3.6/scripts/stimulsoft.reports.js"></script>
  <script type="text/javascript" src="/stimulsoft-report/2021.3.6/scripts/stimulsoft.viewer.js"></script>
  <script type="text/javascript" src="/stimulsoft-report/2021.3.6/scripts/stimulsoft.dashboards.js"></script>
  <script type="text/javascript" src="/stimulsoft-report/2021.3.6/scripts/stimulsoft.designer.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
  <script type="text/javascript">
    function Start() {
      Stimulsoft.Base.StiLicense.loadFromFile("/stimulsoft-report/2021.3.6/stimulsoft/license.php");
      var viewerOptions = new Stimulsoft.Viewer.StiViewerOptions()

      var viewer = new Stimulsoft.Viewer.StiViewer(viewerOptions, "StiViewer", false)
      var report = new Stimulsoft.Report.StiReport()

      var options = new Stimulsoft.Designer.StiDesignerOptions()
      options.appearance.fullScreenMode = true

      var designer = new Stimulsoft.Designer.StiDesigner(options, "Designer", false)

      var dataSet = new Stimulsoft.System.Data.DataSet("Data")

      viewer.renderHtml('content')
      report.loadFile('/report/MasterDetail.mrt')

      report.dictionary.dataSources.clear()

      dataSet.readJson(<?= json_encode($dataPelanggan) ?>)

      report.regData(dataSet.dataSetName, '', dataSet)
      report.dictionary.synchronize()
      report.pages.getByIndex(0).margins = new Stimulsoft.Report.Components.StiMargins(0.5, 0.5, 0.5, 0.5)

      viewer.report = report

      designer.renderHtml("content")
      designer.report = report
    }

    function afterPrint() {
      if (confirm('Tutup halaman?')) {
        window.close()
      }
    }
  </script>
  <style type="text/css">
    .stiJsViewerPage {
      word-break:  break-all !important;
    }

    @media print {
      * {
        -webkit-print-color-adjust: exact !important;
        color-adjust: exact !important;
      }
    }
  </style>
</head>
<body onLoad="Start()" onafterprint="afterPrint()">

  <div id="content"></div>

</body>
</html>
