@php
    // Ejemplo de uso
    //dd($infoNodo);
    //llega esta info
    /*
    {#396
        +"idNodo": 119
        +"PosicionAnterior": null
        +"PosicionSiguiente": "122"
        +"Agente": 11019
        +"Plaza": null
        +"Usuario": 35
        +"FechaDeAlta": "2000-01-01 00:00:00"
        +"CUE": "460019500"
        +"updated_at": "2023-07-24 12:45:07"
        +"created_at": "2023-06-14 06:06:27"
        +"EspacioCurricular": 2226
        +"Division": 650
        +"CargoSalarial": 4
        +"CantidadHoras": 20
        +"SitRev": 4
        +"Asignatura": 650
        +"Observaciones": null
    }
    */
    /*adaptar
    >select(
        'tb_agentes.*',
        'tb_nodos.*',
        'tb_asignaturas.idAsignatura',
        'tb_asignaturas.Descripcion as nomAsignatura',
        'tb_cargossalariales.idCargo',
        'tb_cargossalariales.Cargo as nomCargo',
        'tb_cargossalariales.Codigo as nomCodigo',
        'tb_situacionrevista.idSituacionRevista',
        'tb_situacionrevista.Descripcion as nomSitRev',
        'tb_divisiones.idDivision',
        'tb_divisiones.Descripcion as nomDivision',
        )
    */
    recursiva($infoNodo->idNodo);   //envio la base
    
    
@endphp

