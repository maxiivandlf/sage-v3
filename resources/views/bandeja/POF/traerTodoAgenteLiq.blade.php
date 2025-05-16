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
  const gridOptions = {
    columnDefs: [
      { headerName: "Orden", valueGetter: function(params) { return params.node.rowIndex + 1; }, width: 100, suppressFilter: true, suppressSorting: true },
      { headerName: "#ID", field: "idPofmh", filter: 'agTextColumnFilter' },
      { headerName: "CUE Completo", field: "CUECOMPLETO", filter: 'agTextColumnFilter', enableRowGroup: true },
      { headerName: "Orden", field: "orden", filter: 'agTextColumnFilter' },
      { headerName: "DNI", field: "docu", filter: 'agTextColumnFilter', cellClassRules: { 'dni-cargado': 'data.isDniLoaded === true', 'dni-no-cargado': 'data.isDniLoaded === false' } },
      { headerName: "CUIL", field: "cuil", filter: 'agTextColumnFilter' },
      { headerName: "Agrupación", field: "agru", filter: 'agTextColumnFilter' },
      { headerName: "Apellido y Nombre", field: "ApeNom", filter: 'agTextColumnFilter' },
      { headerName: "Cargo", field: "Cargo", filter: 'agTextColumnFilter' },
      { headerName: "Aula", field: "Aula", filter: 'agTextColumnFilter' },
      { headerName: "División", field: "Division", filter: 'agTextColumnFilter' },
      { headerName: "Esp.Cur", field: "EspCur", filter: 'agTextColumnFilter' },
      { headerName: "Turno", field: "Turno", filter: 'agTextColumnFilter', enableRowGroup: true },
      { headerName: "Horas", field: "Horas", filter: 'agNumberColumnFilter' },
      { headerName: "Origen", field: "Origen", filter: 'agTextColumnFilter' },
      { headerName: "Sit.Rev", field: "SitRev", filter: 'agTextColumnFilter' },
      { headerName: "Fecha Alta Cargo", field: "FechaAltaCargo", filter: 'agDateColumnFilter' },
      { headerName: "Fecha Designado", field: "FechaDesignado", filter: 'agDateColumnFilter' },
      { headerName: "Matricula", field: "Matricula", filter: 'agTextColumnFilter' },
      {
        headerName: "Condición", field: "Condicion", enableRowGroup: true,
        filter: 'agSetColumnFilter',
        filterParams: { applyMiniFilterWhileTyping: true, newRowsAction: 'keep' }
      },
      { headerName: "¿Activo?", field: "Activo", filter: 'agTextColumnFilter' },
      { headerName: "Desde", field: "FechaDesde", filter: 'agDateColumnFilter' },
      { headerName: "Hasta", field: "FechaHasta", filter: 'agDateColumnFilter' },
      { headerName: "Motivo", field: "Motivo", filter: 'agTextColumnFilter' },
      { headerName: "Datos por Condición", field: "DatosPorCondicion", filter: 'agTextColumnFilter' },
      { headerName: "Antigüedad", field: "Antiguedad", filter: 'agTextColumnFilter' },
      { headerName: "DNI Suplente", field: "AgenteR", filter: 'agTextColumnFilter' },
      { headerName: "Observaciones", field: "Observaciones", filter: 'agTextColumnFilter' },
      { headerName: "Unidad Liquidación", field: "Unidad_Liquidacion", filter: 'agTextColumnFilter' },
      { headerName: "Carrera", field: "Carrera", filter: 'agTextColumnFilter' },
      { headerName: "Orientación", field: "Orientacion", filter: 'agTextColumnFilter' },
      { headerName: "Título", field: "Titulo", filter: 'agTextColumnFilter' },
      { headerName: "Zona Supervision", field: "ZonaSupervision", filter: 'agTextColumnFilter', enableRowGroup: true },
      { headerName: "Zona Recursos Humanos", field: "ZonaRecursosHumanos", filter: 'agTextColumnFilter' },
      { headerName: "Zona Liquidación", field: "ZonaLiquidacion", filter: 'agTextColumnFilter' },
      { headerName: "Unidad Liquidación Recibo", field: "Unidad_Liquidacion_Recibo", filter: 'agTextColumnFilter' },
      { headerName: "Trabajo Recibo", field: "Trabajo_Recibo", filter: 'agTextColumnFilter' },
      { headerName: "Descripción Recibo", field: "Descripcion_Recibo", filter: 'agTextColumnFilter' },
      { headerName: "Código Área Recibo", field: "Codigo_Area_Recibo", filter: 'agTextColumnFilter' },
      { headerName: "Nombre Institución", field: "nombreInstitucion", filter: 'agTextColumnFilter' },
      { headerName: "Niveles", field: "niveles", filter: 'agTextColumnFilter' },
      { headerName: "Modalidades", field: "modalidades", filter: 'agTextColumnFilter' },
      { headerName: "Nivel", field: "Nivel", filter: 'agTextColumnFilter', enableRowGroup: true },
      { headerName: "Zona", field: "Zona", filter: 'agTextColumnFilter', enableRowGroup: true },
      { headerName: "Zona CUEA", field: "zonaCUEA", filter: 'agTextColumnFilter' },
      { headerName: "Escuela 1", field: "escu1", filter: 'agTextColumnFilter' },
      { headerName: "Área 1", field: "area1", filter: 'agTextColumnFilter' },
      { headerName: "Escuela 2", field: "escu2", filter: 'agTextColumnFilter' },
      { headerName: "Área 2", field: "area2", filter: 'agTextColumnFilter' },
      { headerName: "Escuela 3", field: "escu3", filter: 'agTextColumnFilter' },
      { headerName: "Área 3", field: "area3", filter: 'agTextColumnFilter' },
      { headerName: "Creado", field: "created_at", filter: 'agDateColumnFilter' },
      { headerName: "Actualizado", field: "updated_at", filter: 'agDateColumnFilter' },
      { headerName: "Novedad", field: "Novedades", filter: 'agTextColumnFilter' },
      { headerName: "Acción", field: "Acciones", filter: 'agTextColumnFilter' }
    ],
    rowData: @json($infoPofMH),
    enableFilter: true,
    groupUseEntireRow: true,
    rowGroupPanelShow: 'always',
    sideBar: {
      toolPanels: ['columns'],
      defaultToolPanel: 'columns'
    },
    onGridReady: function (params) {
      gridOptions.api = params.api;
    }
  };

  const gridDiv = document.querySelector('#agGrid');
  agGrid.createGrid(gridDiv, gridOptions);
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
