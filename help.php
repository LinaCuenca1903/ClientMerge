<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Centro de Ayuda</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      background-color: #f4f4f4;
    }
    h2 {
      text-align: center;
      color: #333;
    }
    .search-box {
      text-align: center;
      margin-bottom: 20px;
    }
    input[type="text"] {
      width: 50%;
      padding: 10px;
      font-size: 16px;
    }
    .faq-item {
      background: white;
      margin: 10px auto;
      padding: 15px;
      border-radius: 6px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      max-width: 700px;
      transition: all 0.3s;
    }
    .question {
      font-weight: bold;
      cursor: pointer;
    }
    .answer {
      display: none;
      padding-top: 8px;
      color: #555;
    }
    .logo {
      margin-top: 40px;
      display: block;
      margin-left: auto;
      margin-right: auto;
      width: 60px;
      height: 60px;
      border-radius: 50%;
    }
  </style>
</head>
<body>

  <h2>Centro de Ayuda</h2>

  <div class="search-box">
    <input type="text" id="buscador" placeholder="Buscar una pregunta..." onkeyup="filtrarPreguntas()">
  </div>

  <div class="faq-item">
    <div class="question" onclick="toggleAnswer(this)">¿Cómo restablecer mi contraseña?</div>
    <div class="answer">Ve al inicio de sesión, haz clic en "¿Olvidaste tu contraseña?" y sigue los pasos.</div>
  </div>

  <div class="faq-item">
    <div class="question" onclick="toggleAnswer(this)">¿Dónde encuentro el historial de casos?</div>
    <div class="answer">En el menú principal, selecciona "Casos" y luego haz clic en "Historial".</div>
  </div>

  <div class="faq-item">
    <div class="question" onclick="toggleAnswer(this)">¿Cómo contacto a un supervisor?</div>
    <div class="answer">Puedes escribir a soporte@clientmerge.com o contactar mediante el chat interno.</div>
  </div>

  <img src="Logo.jpg" class="logo" alt="Logo">

  <script>
    function toggleAnswer(element) {
      const answer = element.nextElementSibling;
      answer.style.display = answer.style.display === "block" ? "none" : "block";
    }

    function filtrarPreguntas() {
      const input = document.getElementById("buscador").value.toLowerCase();
      const faqs = document.getElementsByClassName("faq-item");
      for (let i = 0; i < faqs.length; i++) {
        const pregunta = faqs[i].getElementsByClassName("question")[0].textContent.toLowerCase();
        faqs[i].style.display = pregunta.includes(input) ? "block" : "none";
      }
    }
  </script>

</body>
</html>
