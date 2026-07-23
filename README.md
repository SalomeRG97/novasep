# NOVASEP — Nova Seguridad Privada Ltda.

Sitio web corporativo de **Nova Seguridad Privada Ltda. (NOVASEP)**, empresa colombiana de seguridad privada con presencia nacional en 15+ departamentos.

> 🔗 **URL de producción:** [www.novaseguridad.com.co](https://www.novaseguridad.com.co/)

---

## 📋 Descripción

Sitio estático multipage construido con HTML, CSS y JavaScript vanilla. Diseño oscuro + rojo + blanco con enfoque premium, fully responsive para móvil, tablet y escritorio.

### Páginas

| Archivo | Página |
|---|---|
| `index.html` | Inicio (home) |
| `servicios.html` | Catálogo de servicios |
| `srv-vigilancia-humana.html` | Servicio — Vigilancia humana |
| `srv-escoltas.html` | Servicio — Escoltas |
| `srv-drones.html` | Servicio — Drones de seguridad |
| `srv-seguridad-electronica.html` | Servicio — Seguridad electrónica |
| `srv-analisis-riesgos.html` | Servicio — Análisis de riesgos |
| `sectores.html` | Sectores atendidos |
| `certificaciones.html` | Certificaciones (ISO 9001, 45001, 14001) |
| `quienes-somos.html` | Quiénes somos |
| `trabaja.html` | Trabaja con nosotros |
| `contacto.html` | Contacto y cotización |

---

## 🛠 Stack Tecnológico

| Componente | Tecnología |
|---|---|
| Estructura | HTML5 semántico |
| Estilos | CSS3 vanilla (custom properties, clamp, grid, flexbox) |
| Interacción | JavaScript vanilla (ES5 compatible) |
| Tipografía | [Archivo](https://fonts.google.com/specimen/Archivo) + [IBM Plex Sans](https://fonts.google.com/specimen/IBM+Plex+Sans) (Google Fonts) |
| Iconos | SVG sprite generado dinámicamente (`main.js`) |
| Formularios | [EmailJS](https://www.emailjs.com/) (envío client-side) |
| Mapa | SVG interactivo de Colombia (`media/colombia-map.svg`) |

---

## 🚀 Cómo Ejecutar Localmente

Este es un sitio estático — no requiere build ni dependencias. Solo necesitas un servidor HTTP local:

```bash
# Opción 1: Python
python -m http.server 8000

# Opción 2: Node.js (npx)
npx serve .

# Opción 3: VS Code
# Instalar extensión "Live Server" → Click derecho en index.html → "Open with Live Server"
```

Luego abre `http://localhost:8000` en tu navegador.

---

## 📐 Sistema de Diseño

### Colores (CSS Custom Properties)

| Variable | Valor | Uso |
|---|---|---|
| `--color-primary` | `#cc0000` | Rojo principal, CTAs, acentos |
| `--color-accent` | `#e00000` | Hover sobre rojo |
| `--color-dark` | `#0a0a0a` | Fondo oscuro principal |
| `--color-surface` | `#111111` | Superficies elevadas |
| `--color-text-light` | `#ffffff` | Texto sobre fondo oscuro |
| `--color-text-muted` | `#a0a0a0` | Texto secundario |

### Breakpoints Responsive

| Dispositivo | Rango | Comportamiento |
|---|---|---|
| 📱 Móvil | `≤ 767px` | 1 columna, paddings compactos, hero reducido |
| 📲 Tablet | `768px – 1024px` | 2 columnas, espaciado intermedio |
| 💻 Desktop | `> 1024px` | Multicolumna completa |
| 🖥 Ultrawide | `> 1440px` | Contenedor limitado a 1400px |

### Tipografía

- **Display:** Archivo (títulos, navegación, elementos destacados)
- **Body:** IBM Plex Sans (texto, formularios, labels)

---

## ⚙️ Configuración de EmailJS

Los formularios de contacto y hojas de vida usan **EmailJS** para envío client-side. Para activar el envío:

1. Crear cuenta en [emailjs.com](https://www.emailjs.com/)
2. Configurar un servicio de email (Gmail, Outlook, etc.)
3. Crear templates para contacto y hojas de vida
4. Abrir `main.js` y reemplazar las constantes en `EMAILJS_CONFIG`:

```javascript
var EMAILJS_CONFIG = {
  publicKey: 'TU_PUBLIC_KEY',
  serviceId: 'TU_SERVICE_ID',
  templateIdContact: 'TU_TEMPLATE_CONTACTO',
  templateIdCV: 'TU_TEMPLATE_CV'
};
```

---

## 📁 Estructura del Proyecto

```
NOVASEP-site/
├── index.html                    # Página de inicio
├── servicios.html                # Catálogo de servicios
├── srv-vigilancia-humana.html    # Servicio individual
├── srv-escoltas.html             # Servicio individual
├── srv-drones.html               # Servicio individual
├── srv-seguridad-electronica.html # Servicio individual
├── srv-analisis-riesgos.html     # Servicio individual
├── sectores.html                 # Sectores económicos
├── certificaciones.html          # Certificaciones ISO
├── quienes-somos.html            # Sobre la empresa
├── trabaja.html                  # Trabaja con nosotros
├── contacto.html                 # Formulario de contacto
├── styles.css                    # Hoja de estilos principal
├── main.js                       # Lógica, header/footer, iconos, formularios
├── media/                        # Imágenes y assets
│   ├── logo.png                  # Logo versión clara
│   ├── logo negro.png            # Logo versión oscura
│   ├── colombia-map.svg          # Mapa SVG interactivo
│   ├── index/                    # Imágenes del home
│   ├── servicios/                # Imágenes de servicios
│   └── sectores/                 # Imágenes de sectores
├── manifest.json                 # PWA manifest
├── robots.txt                    # Directivas para crawlers
├── sitemap.xml                   # Sitemap XML
├── favicon.ico                   # Favicon
└── .gitignore
```

---

## 📄 Licencia

© 2024–2026 Nova Seguridad Privada Ltda. Todos los derechos reservados.

Este código es propiedad de Nova Seguridad Privada Ltda. y no se permite su reproducción, distribución o uso sin autorización expresa.
# novasep
