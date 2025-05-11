@extends('layout.app')

@section('Titulo', 'Sage2.0 - Nuevo Usuario ADMIN')
@section('LinkCSS')
  {{--<link rel="stylesheet" href="{{ asset('css/pofmh.css') }}">
  <link rel="stylesheet" href="{{ asset('css/vistaliq.css') }}">--}}
  <script src="https://unpkg.com/ag-grid-enterprise/dist/ag-grid-enterprise.min.js"></script>
  {{--<script src="https://unpkg.com/xlsx@0.17.0/dist/xlsx.full.min.js"></script>--}}

  <style>
    .dni-cargado {
      background-color: lightgreen !important; /* Fondo verde claro */
    }
    .dni-no-cargado {
      background-color: lightcoral !important; /* Fondo rojo claro */
    }
  </style>
@endsection
@section('ContenidoPrincipal')

<section id="container">
    <section id="main-content">
        <section class="content-wrapper">
                <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    
                    <div id="agGrid" class="ag-theme-quartz" style="height: 600px; width: 100%;"></div>
                    <button class="btn btn-success" id="exportBtn" onclick="exportToExcel()">Exportar a Excel</button>

                </div>    
            </section>
            <!-- /.content -->
        </section>
    </section>
</section>
@endsection

@section('Script')
 

<script>
  var gridOptions;
  document.addEventListener('DOMContentLoaded', function () {
    gridOptions = {
      columnDefs: [
        { headerName: "Orden", valueGetter: function(params) { return params.node.rowIndex + 1; }, width: 100, suppressFilter: true, suppressSorting: true },
        { headerName: "CUE Completo", field: "CUECOMPLETO", filter: 'agTextColumnFilter', enableRowGroup: true },
        { headerName: "Nivel", field: "Nivel", filter: 'agTextColumnFilter' },
        { headerName: "Zona", field: "Zona", filter: 'agTextColumnFilter' },
        { headerName: "ZonaSupervision", field: "ZonaSupervision", filter: 'agTextColumnFilter' },
        { headerName: "#ID", field: "idPofmh", filter: 'agTextColumnFilter' },
        { headerName: "Orden", field: "orden", filter: 'agTextColumnFilter' },
        { headerName: "DNI", field: "Agente", filter: 'agTextColumnFilter', cellClassRules: { 'dni-cargado': 'data.isDniLoaded === true', 'dni-no-cargado': 'data.isDniLoaded === false' } },
        { headerName: "Apellido y Nombre", field: "ApeNom", filter: 'agTextColumnFilter' },
        { headerName: "Cargo de Origen en la Institución", field: "Origen", filter: 'agTextColumnFilter' },
        { headerName: "Sit.Rev", field: "SitRev", filter: 'agTextColumnFilter' },
        { headerName: "Horas", field: "Horas", filter: 'agNumberColumnFilter' },
        { headerName: "Antigüedad Docente", field: "Antiguedad", filter: 'agTextColumnFilter' },
        { headerName: "Código Cargo", field: "Cargo", filter: 'agTextColumnFilter' },
        { headerName: "Aula", field: "Aula", filter: 'agTextColumnFilter' },
        { headerName: "División", field: "Division", filter: 'agTextColumnFilter' },
        { headerName: "Turno", field: "Turno", filter: 'agTextColumnFilter' },
        { headerName: "Esp.Cur", field: "EspCur", filter: 'agTextColumnFilter' },
        { headerName: "Matricula", field: "Matricula", filter: 'agTextColumnFilter' },
        { headerName: "Posesión del Cargo", field: "FechaAltaCargo", filter: 'agDateColumnFilter' },
        { headerName: "Designado al cargo", field: "FechaDesignado", filter: 'agDateColumnFilter' },
        { headerName: "Condición", field: "Condicion", filter: 'agSetColumnFilter', filterParams: { applyMiniFilterWhileTyping: true, newRowsAction: 'keep' } },
        { headerName: "¿En función en el cargo?", field: "Activo", filter: 'agTextColumnFilter' },
        { headerName: "Tipo-Motivo-Art.Licenica", field: "Motivo", filter: 'agTextColumnFilter' },
        { headerName: "Otros Datos por Condición", field: "DatosPorCondicion", filter: 'agTextColumnFilter' },
        { headerName: "Desde", field: "FechaDesde", filter: 'agDateColumnFilter' },
        { headerName: "Hasta", field: "FechaHasta", filter: 'agDateColumnFilter' },
        { headerName: "DNI Suplente", field: "AgenteR", filter: 'agTextColumnFilter' },
        { headerName: "Novedad", field: "Novedades", filter: 'agTextColumnFilter' },
        { headerName: "Observaciones", field: "Observaciones", filter: 'agTextColumnFilter' },
        { headerName: "Carrera", field: "Carrera", filter: 'agTextColumnFilter' },
        { headerName: "Orientación", field: "Orientacion", filter: 'agTextColumnFilter' },
        { headerName: "Título", field: "Titulo", filter: 'agTextColumnFilter' },
        { headerName: "Acción", field: "Acciones", filter: 'agTextColumnFilter' },
        { headerName: "ZonaSupervision", field: "ZonaSupervision", filter: 'agTextColumnFilter' }
      ],
      rowData: @json($infoPofMH),
      enableFilter: true,
      groupUseEntireRow: true,
      onGridReady: function (params) {
            gridOptions.api = params.api; //dejarlo porque lo requiere la funcion de exportar
        }
    };

    var gridDiv = document.querySelector('#agGrid');
    new agGrid.createGrid(gridDiv, gridOptions);
    
  });
  

  //aqui dejo la funcion de exportar de mi amigo chatgpt
  function exportToExcel() {
    gridOptions.api.exportDataAsExcel({
        fileName: 'reporte-SAGE.xlsx',
        sheetName: 'Datos',
        allColumns: true, 
        columnKeys: null, 
        onlySelected: false, 
        processCellCallback: function(params) {
            return params.value || '';
        },
        processHeaderCallback: function(params) {
            return params.column.getColDef().headerName;
        }
    });
  }

</script>


@endsection
