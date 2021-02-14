# AgrOS Mockups

## Registro de usuario

```plantuml
@startsalt
{+
  skinparam handwritten true
  Email    | "mi_nombre@email.com "
  Password | "****                "
  Pwd Confirmación | "****                "
  [] Acepto condiciones
  <color:Blue>__Leer términos y condiciones__
  <color:Blue>__¿Ya tienes una cuenta?__
  [Registrar]
}
@endsalt
```

## Entrar al sistema

```plantuml
@startsalt
{+
  skinparam handwritten true
  Email    | "mi_nombre@email.com "
  Password | "****                "
  <color:Blue>__recordar contraseña__
  <color:Blue>__¿Todavía no tienes cuenta?__
  [Entrar]
}
@endsalt
```

## Panel de control

```plantuml
@startsalt
{
  skinparam handwritten true
  skinparam FontName Comic Sans MS
  {* <&image> | Panel | Actividades | Configuración | Usuario }
  {
    <size:18>Panel de control
    {^"<size:14>Calendario <color:Blue>__<<__ mes/año __>>__"
      {#
        <color:Blue>L | <color:Blue>M | <color:Blue>X | <color:Blue>J | <color:Blue>V | <color:Blue>S | <color:Blue>D
        1|<color:Green><size:14>__2__|3|4|<color:Green><size:14>__5__|<color:Green><size:14>__6__|7
        8|9|10|11|12|13|14
        15|16|17|18|19|20|21
        22|23|24|25|26|27|28
        29|30|31|<color:#aaa>1|<color:#aaa>2|<color:#aaa>3|<color:#aaa>4
      }|{#
        <color:Blue>L | <color:Blue>M | <color:Blue>X | <color:Blue>J | <color:Blue>V | <color:Blue>S | <color:Blue>D
        1|<color:Green><size:14>__2__|3|4|<color:Green><size:14>__5__|<color:Green><size:14>__6__|7
        8|9|10|11|12|13|14
        15|16|17|18|19|20|21
        22|23|24|25|26|27|28
        29|30|31|<color:#aaa>1|<color:#aaa>2|<color:#aaa>3|<color:#aaa>4
      }
    }

    {^"<size:14>Últimas Actividades"
      {#
        Fecha|Actividad|Tipo|Cantidad|Enlace
        06/03/2020|Plantar|Tomates|100|<color:Blue>__Ver__
        05/03/2020|Preparar tierra|Parcela A|12m2|<color:Blue>__Ver__
        05/03/2020|Comprar semillas|Varias|1000|<color:Blue>__Ver__
        02/03/2020|Análisis suelo|General|1|<color:Blue>__Ver__
        02/03/2020|Análisis agua|General|1|<color:Blue>__Ver__
      }
    }|*
    [+ Nueva Actividad] | [Generar Cuaderno]
  }
}
@endsalt
```

## Listado de variedades

```plantuml
@startsalt
{+
  skinparam handwritten true
  Email    | "mi_nombre@email.com "
  Password | "****                "
  Pwd Confirmación | "****                "
  [] Acepto condiciones
  <color:Blue>__Leer términos y condiciones__
  <color:Blue>__¿Ya tienes una cuenta?__
  [Registrar]
}
@endsalt
```