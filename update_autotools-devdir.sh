#!/bin/bash

#Importa las variables y funciones del script scripts/config.sh
source scripts/config.sh

update_autotoolsdir(){

 tempdir=$1

 if [ -z $tempdir ];then 
  tempdir=`$ALMIDONDIR`; #El valor de tempdir si es un valor nulo, entonces almacena la
                         #ruta absoluta de  que almacena la variable ALMIDONDIR.
 fi

 length_from_tempdir="${#tempdir}"
 last_char_from_tempdir="`expr substr $length_from_tempdir 1`";

 #Si el valor del tempdir posee como último cáracter un valor igual a /,entonces
 #se procede a extraer una subcadena a partir de la posición inicial de la cadena que almacena
 #la variable tempdir hasta la longitud de la misma menos 1, ya que no queremos incluir el
 #último cáracter.

 if [ $last_char_from_tempdir = "/" ];then
  tempdir="${1:0:$length_from_tempdir-1}";
 fi

 #La variable almidon_dir almacena la ruta absoluta del directorio  donde está guardado
 #por defecto el directorio autotools-dev.

 almidon_dir=$tempdir
 length_from_almidon_dir="${#almidon_dir}"

 tempdir2=$2

 if [ -z $tempdir2 ];then 
  tempdir2=`$almidon_dir/autotools-dev`; #El valor de tempdir2 si es un valor nulo, entonces 
                                         #almacena la ruta absoluta del directorio de almidon que almacena la variable almidon_dir seguido del caracter de separator "/" y por el último de la cadena autotools-dev.
 fi

 length_from_tempdir2="${#tempdir2}"
 last_char_from_tempdir2="`expr substr $length_from_tempdir2 1`";

 #Si el valor del tempdir2 posee como último cáracter un valor igual a /,entonces
 #se procede a extraer una subcadena a partir de la posición inicial de la cadena que almacena
 #la variable tempdir2 hasta la longitud de la misma menos 1, ya que no queremos incluir el
 #último cáracter.

 if [ $last_char_from_tempdir2 = "/" ];then
  tempdir2="${tempdir2:0:$length_from_tempdir2-1}";
 fi

 autotools_dev_dir=$temp2

 #Si existe el directorio autotools-dev( denotado por la ruta absoluta que representa el valor  
 #de la variable autotools_dev_dir) borra sólo su contenido más no el directorio en sí.
 if [ -d $autotools_dev_dir ];then
  rm -Rf $autotools_dev_dir/*;
 else
      #Si no existe el directorio autotools-dev, entonces se crea un directorio vacío.
      mkdir $autotools_dev_dir
 fi

 #Eliminación de todos los archivos Makefile dentro del directorio 
 #Arreglo de archivos cuyo nombre es Makefile
 array_from_files_with_filename_Makefile=(`find $almidon_dir -name "Makefile" -print`);
 size_from_files_with_filename_Makefile=${#array_from_files_with_exts[@]}

 counter=0;
 while [ $counter -lt $size_from_files_with_filename_Makefile ];do
  filePath=${array_from_files_with_filename_Makefile[$counter]}; 
  if [ -f $filePath ];then
   rm -f $filePath;
  fi
  counter="`expr $counter + 1`"
 done

 #Copia de los archivos necesarios guardado en la carpeta  hacia autotools-dev

 #Copia sólo los archivos del directorio  hacia autotools-dev
 #Arreglo que guarda el contenido del directorio cuya ruta almacena la variables almidon_dir. 
 array_from_files_without_ext="`ls $almidon_dir`"

 for element in ${array_from_files_without_ext[*]};do
  #Sólo copia los archivos sin extensión a directorio autotools-dev.
  if [ -f $almidon_dir/$element ];then
   if [ $element != "Makefile "];then 
    cp $almidon_dir/$element  $autotools_dev_dir
   fi
  fi
 done

 #Copia el contenido del directorio .anjuta al directorio autotools-dev
 if [ -d "$almidon_dir/.anjuta" ];then
  cp -Rf "$almidon_dir/.anjuta" $autotools_dev_dir
 fi

 #Copia de los archivos con ciertas extensiones predeterminadas
 #Arreglo de archivos con ciertas extensiones predeterminadas
 array_from_files_with_exts=("ac" "am" "anjuta" "guess" "h" "in" "log" "m4" "sh" "status" "sub" "tasks")

 #Tamaño de cada arreglo que guarda las rutas absolutas de cada archivo que concuerdan con las  
 #extensiónes predeterminada.
 size_from_files_with_exts=${#array_from_files_with_exts[@]}

 main_counter=0;
 while [ $main_counter -lt $size_from_files_with_exts ];do #Inicio del 1º While
  #La variable extension almacena los archivos que queremos encontrar por su extensión.
  extension=${array_from_files_with_exts[$main_counter]}

  #Almacena sólo las rutas absolutas de los archivos que concuerdan con una extensión dada.
  array_from_files_with_ext=(`find $almidon_dir -name "*.$extension" -print`);
 
  #Tamaño de cada arreglo que guarda las rutas absolutas de cada archivo que concuerdan con una  extensión dada.
  size_from_files_with_ext=${#array_from_files_with_ext[@]}

  aux_counter=0;
  while [ $aux_counter -lt $size_from_files_with_ext ];do #Inicio del 2º While
   # Almacena la ruta del archivo que posee la extensión denotaba por el valor de 
   # ${array_from_files_with_exts[$main_counter]}
   filePathSrc=${array_from_files_with_ext[$aux_counter]};
   #Por ejemplo: /ruta/hacia/el/directorio/de//core/cloudfiles/Makefile.am

   #Nombre completo del archivo origen.
   filename_from_src="${filePathSrc##*/}";
   #Por ejemplo: Makefile.am

   #Longitud de la ruta es el total de caracteres de la ruta absoluta del archivo menos el largo
   #del nombre de archivo.
   length_from_parent_dir_from_filePathSrc="`expr ${#filePathSrc} - ${#filename_from_src}`" 

   #Ruta absoluta del directorio padre del archivo origen
   parent_dir_from_filePathSrc="${filePathSrc:0:$length_from_parent_dir_from_filePathSrc-1}";
   #Por ejemplo: /ruta/hacia/el/directorio/de//core/cloudfiles

   #Nombre del directorio padre
   filename_from_parent_dir_from_src="${parent_dir_from_filePathSrc##*/}";
   #Por ejemplo: cloudfiles

   #Jerarquía de directorios incluidos dentro del directorio padre que se encuentra entre la 
   #ruta absoluta del directorio de  y el nombre completo de archivo origen.
   hierarchy_parent_dir_from_filePathSrc=${parent_dir_from_filePathSrc:$length_from_almidon_dir+1:$length_from_parent_dir_from_filePathSrc};
   #Por ejemplo: core/cloudfiles

   #Ruta absoluta del directorio padre del archivo destino
   parent_dir_from_filePathDst=$autotools_dev_dir/$hierarchy_parent_dir_from_filePathSrc;
   #Por ejemplo: /ruta/hacia/el/directorio/de/autotools-dev/core/cloudfiles

   #Nombre completo del archivo destino.
   filename_from_dst=$filename_from_src;
   #Por ejemplo: Makefile.am

   #Ruta absoluta del archivo destino.
   filePathDst=$parent_dir_from_filePathDst/$filename_from_dst;
   #Por ejemplo: /ruta/hacia/el/directorio/de/autotools-dev/core/cloudfiles/Makefile.am

   if [ -f $filePathSrc ];then
    if [ $parent_dir_from_filePathSrc != $autotools_dev_dir ];then
     if [ $filename_from_parent_dir_from_src != "debian" ];then 
      if [ $filename_from_parent_dir_from_src != "scripts" ];then
       if [ ! -d $parent_dir_from_filePathDst ];then
        #Crea el directorio dentro de la jerarquía de directorios de la carpeta 
        #para copiar dentro de él precisamente el archivo destino.
        mkdir $parent_dir_from_filePathDst;
       fi
       #Copia cada archivo que posea  hacia autotools-dev
       cp -f -p $filePathSrc $filePathDst;
       #Elimina el archivo origen para que ya no exista dentro de la jerarquía de subdirectorios
       #y archivos que hay dentro del directorio , ya que existe una copia del mismo
       #dentro de la carpeta autotools-dev.
       rm -f $filePathSrc;
     fi
    fi
   fi 
   aux_counter="`expr $aux_counter + 1`"
  done #Fin del 2º While
  main_counter="`expr $main_counter + 1`"
 done #Fin del 1º While
}

update_autotoolsdir $ALMIDONDIR $ALMIDONDIR/autotools-dev



