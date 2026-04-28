// Interaktywność kalendarza i formularza
document.addEventListener('DOMContentLoaded', function() {
  const calendarDays = document.querySelectorAll('.calendar-day:not(.empty)');
  const dataInput = document.getElementById('data');
  
  // Kliknięcie na dzień w kalendarzu
  calendarDays.forEach(day => {
    day.addEventListener('click', function() {
      const date = this.getAttribute('data-date');
      if(date) {
        dataInput.value = date;
        dataInput.focus();
        // Scroll do formularza
        document.querySelector('.mood-form-section').scrollIntoView({ behavior: 'smooth' });
      }
    });
  });

  // Auto-fokus na godzinę po wybraniu daty
  dataInput.addEventListener('change', function() {
    document.getElementById('godzina').focus();
  });

  // Validacja formularza
  const form = document.querySelector('.mood-form');
  if(form) {
    form.addEventListener('submit', function(e) {
      const data = document.getElementById('data').value;
      const godzina = document.getElementById('godzina').value;
      const nastroj = document.getElementById('nastroj').value;

      if(!data || !godzina || !nastroj) {
        e.preventDefault();
        alert('Proszę wypełnić wszystkie wymagane pola');
        return false;
      }
    });
  }

  // Potwierdzenie przed usunięciem
  const deleteButtons = document.querySelectorAll('.entry-delete-form');
  deleteButtons.forEach(form => {
    form.addEventListener('submit', function(e) {
      if(!confirm('Czy na pewno chcesz usunąć ten wpis?')) {
        e.preventDefault();
      }
    });
  });

  // Automatyczne zamknięcie komunikatów
  const messages = document.querySelectorAll('.message');
  messages.forEach(msg => {
    setTimeout(() => {
      msg.style.transition = 'opacity 0.5s ease';
      msg.style.opacity = '0';
      setTimeout(() => {
        msg.style.display = 'none';
      }, 500);
    }, 4000);
  });

  // Animacja przy ładowaniu stron
  const entryCards = document.querySelectorAll('.entry-card');
  entryCards.forEach((card, index) => {
    card.style.animation = `slideIn 0.3s ease ${index * 0.1}s both`;
  });
});

// Animacja slideIn
const style = document.createElement('style');
style.textContent = `
  @keyframes slideIn {
    from {
      opacity: 0;
      transform: translateX(-20px);
    }
    to {
      opacity: 1;
      transform: translateX(0);
    }
  }
`;
document.head.appendChild(style);

// Zmiana treści podczas pisania w opisie
const descriptionInput = document.getElementById('opis');
if(descriptionInput) {
  descriptionInput.addEventListener('input', function() {
    const maxLength = this.getAttribute('maxlength');
    const currentLength = this.value.length;
    // Można dodać licznik znaków tutaj jeśli potrzeba
  });
}
