#!/bin/bash

#Importa las variables y funciones del script scripts/config.sh
source scripts/config.sh

update_autotoolsdir(){

#La variable current_dir almacena la ruta absoluta del directorio almidon donde está guardado
#por defecto el directorio autotools-dev.
current_dir=$1
if [ -z $current_dir ];then
 current_dir=`$ALMIDONDIR`; #El valor de current_dir si es un valor nulo, entonces almacena la
                            #ruta absoluta de almidon que almacena la variable ALMIDONDIR.
fi

autotools_dev_dir="$current_dir/autotools-dev"

#Si existe el directorio autotools-dev( denotado por la ruta absoluta que representa el valor de la variable autotools_dev_dir) borra sólo su contenido más no el directorio en sí.
if [ -d $autotools_dev_dir ];then
 rm -Rf $autotools_dev_dir/*;
else
     #Si no existe el directorio autotools-dev, entonces se crea un directorio vacío.
     mkdir $autotools_dev_dir
fi

#Eliminación de todos los archivos Makefile dentro del directorio almidon
#Arreglo de archivos cuyo nombre es Makefile
array_from_files_with_filename_Makefile=(`find $current_dir -name "Makefile" -print`);
size_from_files_with_filename_Makefile=${#array_from_files_with_exts[@]}

counter=0;
while [ $counter -lt $size_from_files_with_filename_Makefile ];do
 filePath=${array_from_files_with_filename_Makefile[$counter]}; 
 if [ -f $filePath ];then
  rm -f $filePath;
 fi
 counter="`expr $counter + 1`"
done

#Copia de los archivos necesarios guardado en la carpeta almidon hacia autotools-dev

#Copia sólo los archivos del directorio almidon hacia autotools-dev
#Arreglo que guarda el contenido del directorio cuya ruta almacena la variables current_dir. 
array_from_files_without_ext="`ls $current_dir`"

for element in ${array_from_files_without_ext[*]};do
 #Sólo copia los archivos sin extensión a directorio autotools-dev.
 if [ -f $current_dir/$element ];then
  if [ $element != "Makefile "];then 
   cp $current_dir/$element  $autotools_dev_dir
  fi
 fi
done

#Copia el contenido del directorio .anjuta al directorio autotools-dev
if [ -d "$current_dir/.anjuta" ];then
 cp -Rf "$current_dir/.anjuta" $autotools_dev_dir
fi

#Copia de los archivos con ciertas extensiones predeterminadas
#Arreglo de archivos con ciertas extensiones predeterminadas
array_from_files_with_exts=("ac" "am" "anjuta" "guess" "h" "in" "log" "m4" "sh" "status" "sub" "tasks")

#Tamaño de cada arreglo que guarda las rutas absolutas de cada archivo que concuerdan con las extensiónes predeterminada.
size_from_files_with_exts=${#array_from_files_with_exts[@]}

main_counter=0;
while [ $main_counter -lt $size_from_files_with_exts ];do #Inicio del 1º While
 #La variable extension almacena los archivos que queremos encontrar por su extensión.
 extension=${array_from_files_with_exts[$main_counter]}

 #Almacena sólo las rutas absolutas de los archivos que concuerdan con una extensión dada.
 array_from_files_with_ext=(`find $current_dir -name "*.$extension" -print`);
 
 #Tamaño de cada arreglo que guarda las rutas absolutas de cada archivo que concuerdan con una  extensión dada.
 size_from_files_with_ext=${#array_from_files_with_ext[@]}

 aux_counter=0;
 while [ $aux_counter -lt $size_from_files_with_ext ];do #Inicio del 2º While
  # Almacena la ruta del archivo que posee la extensión denotaba por el valor de 
  # ${array_from_files_with_exts[$main_counter]}
  filePathSrc=${array_from_files_with_ext[$aux_counter]};

  #Nombre completo del archivo origen.
  filename_from_src="{$filePathSrc##*/}";

  #Longitud de la ruta es el total de caracteres de la ruta absoluta del archivo menos el largo
  #del nombre de archivo.
  length_from_parent_dir_from_filePathSrc="`expr ${#filePathSrc} - ${#filename_from_src}`" 

  #Ruta del directorio padre del archivo origen
  parent_dir_from_filePathSrc="${filePathSrc:0:$length_from_parent_dir_from_filePathSrc-1}";

  #Nombre del directorio padre del archivo origen
  filename_from_parent_dir_from_src="{$parent_dir_from_filePathSrc##*/}";


  #Nombre del directorio padre del archivo destino
  filename_from_parent_dir_from_dst=$filename_from_parent_dir_from_src;

  #Ruta del directorio padre del archivo destino
  parent_dir_from_filePathDst=$autotools_dev_dir/$filename_from_parent_dir_from_src;

  #Nombre completo del archivo destino.
  filename_from_dst=$filename_from_src;

  #Ruta absoluta del archivo destino.
  filePathDst=$parent_dir_from_filePathDst/$filename_from_dst;

  if [ -f $filePathSrc ];then
   if [ $parent_dir_from_filePathSrc != $autotools_dev_dir ];then
    if [ $filename_from_parent_dir_from_src != "debian" ];then 
     if [ $filename_from_parent_dir_from_src != "scripts" ];then
      if [ ! -d $parent_dir_from_filePathDst ];then
       #Crea el directorio dentro de la jerarquía de directorios de la carpeta almidon
       #para copiar dentro de él precisamente el archivo destino.
       mkdir $parent_dir_from_filePathDst;
      fi
      #Copia cada archivo que posea almidon hacia autotools-dev
      cp -f -p $filePathSrc $filePathDst;
      #Elimina el archivo origen para que ya no exista dentro de la jerarquía de subdirectorios
      #y archivos que hay dentro del directorio almidon, ya que existe una copia del mismo
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

update_autotoolsdir $ALMIDONDIR



