
document.getElementById('year').textContent = new Date().getFullYear();

// Ajax form submission with visual feedback
const form = document.getElementById('contactForm');
const feedback = document.getElementById('formFeedback');

form.addEventListener('submit', async function(e){
  e.preventDefault();
  feedback.hidden = true;
  feedback.className = 'feedback';
  feedback.textContent = 'Enviando...';
  feedback.hidden = false;

  const data = new FormData(form);

  try{
    const resp = await fetch('send_mail.php', {
      method: 'POST',
      body: data
    });
    const json = await resp.json();
    if(json.success){
      feedback.classList.add('success');
      feedback.textContent = 'Mensaje enviado correctamente. Gracias por contactarnos.';
      form.reset();
    } else {
      feedback.classList.add('error');
      feedback.textContent = json.error || 'Ocurrió un error enviando el mensaje. Por favor intenta de nuevo.';
    }
  } catch(err){
    feedback.classList.add('error');
    feedback.textContent = 'No se pudo conectar con el servidor. Intenta más tarde.';
  }
});
