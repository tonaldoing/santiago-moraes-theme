# Manual: Sección de Álbumes

Guía para administrar los álbumes del tema Santiago Moraes desde el panel de WordPress.

---

## 1. Acceso

En el admin de WordPress: **Álbumes** (menú lateral izquierdo).

Los álbumes se modelan como **términos de la taxonomía `album`**. Cada álbum agrupa canciones (CPT `cancion`).

---

## 2. Crear un álbum

1. Ir a **Álbumes → Agregar Nuevo Álbum**.
2. Completar el **Nombre** (ej: "Hogar").
3. Opcional: escribir el **Slug** (la parte de la URL). Si se deja vacío, WP lo genera solo.
4. Completar los campos extra (ver más abajo).
5. Click en **Agregar Nuevo Álbum**.

El álbum queda visible en:
- `/album/{slug}/` → página individual del álbum
- Página **Musica** (template "Musica")
- Home, en la sección de música (si está seleccionado como destacado)

---

## 3. Campos de un álbum

Se editan en **Álbumes → pasar el mouse por el álbum → Editar**.

### 3.1. Año
Año de lanzamiento. Se muestra en la portada del álbum y en las tarjetas de discografía.

### 3.2. Orden
- **Número** manual: el álbum con menor número aparece primero.
- Se usa en: página Musica, bloque "Grilla de álbumes", y sección "Mas discografia" de cada álbum.
- Los álbumes **sin** orden se muestran después de los ordenados, ordenados por **año (más reciente primero)**.
- Sugerencia: asigná ordenes consecutivos (1, 2, 3...) a toda la discografía para tener control total.

### 3.3. Descripción
Texto corto de presentación. Se muestra en la página individual del álbum y en la home.

### 3.4. Imagen de portada
Usa el **selector de medios** de WordPress:

1. Click en **Subir imagen**.
2. Elegir una imagen de la biblioteca o subir una nueva.
3. Click en **Usar esta imagen**.
4. Se muestra un preview en miniatura.
5. Para cambiarla: **Cambiar imagen**.
6. Para quitarla: **Eliminar**.

El original se guarda por ID de attachment, así que:
- Queda optimizada y con lazy loading en el front (no se sirve a tamaño completo innecesariamente).
- Si la imagen no se muestra o querés reemplazarla, usá este selector (no pegar URLs).

### 3.5. Es demo / descarte
Checkbox para marcar álbumes de demos/descartes. Los demos aparecen al final de la página Musica y llevan una etiqueta "Demo / Descarte".

### 3.6. URLs de streaming
- **Spotify URL**, **Bandcamp URL**, **YouTube URL**, **Vinilo URL**.
- Si están cargadas, aparecen como botones en la página individual del álbum.
- Pegar URLs completas (ej: `https://open.spotify.com/album/...`).

---

## 4. Asignar canciones a un álbum

Cada canción pertenece a un álbum vía la casilla **Álbumes** en el editor de la canción:

1. Ir a **Canciones** y abrir la canción.
2. En el panel lateral, en **Álbumes**, tildar el álbum.
3. Guardar.

> Nota: `Canciones → Editar → Detalles` también tiene un campo de texto "Album" (`_cancion_album`). Ese campo es informativo/legado; la agrupación real es la taxonomía Álbumes.

---

## 5. Ordenar canciones dentro de un álbum

El tracklist de cada álbum se ordena por **Orden** de la canción y luego por **título** (alfabético).

Para cambiar el orden:

1. Ir a **Canciones**.
2. Click en **Ordenar** (debajo del título en la lista, o en "Edición rápida").
3. Cambiar el valor **Orden** (números: 1, 2, 3...).
4. Guardar.

Las canciones sin orden se listan después, alfabéticamente.

---

## 6. Seleccionar el álbum destacado de la home

La sección "Ultimo disco" de la home muestra un álbum destacado:

1. Ir a **Apariencia → Santiago Moraes → Musica → Album Destacado**.
2. Elegir el álbum.
3. Guardar.

Si no hay ninguno seleccionado, se usa automáticamente el álbum más reciente (orden manual primero, luego por año) que no sea demo.

---

## 7. Borrar un álbum

**Álbumes → pasar el mouse → Borrar**.

Esto **no borra las canciones**: solo quita la agrupación. Las canciones quedan publicadas pero sin álbum.