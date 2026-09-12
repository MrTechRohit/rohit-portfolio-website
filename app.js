document.addEventListener('DOMContentLoaded', () => {
  // Asynchronously load resume details from PHP
  fetchResumeData();

  // Contact form submission via AJAX
  const contactForm = document.getElementById('contactForm');
  contactForm.addEventListener('submit', handleContactSubmit);
});

async function fetchResumeData() {
  try {
    const response = await fetch('api.php');
    if (!response.ok) throw new Error(`HTTP error: ${response.status}`);

    const payload = await response.json();
    const data = payload.data;

    // Set text content
    document.getElementById('heroName').textContent = data.name;
    document.getElementById('heroTitle').textContent = data.title;
    document.getElementById('heroSummary').textContent = data.summary;

    // Set dynamic assets
    if (data.avatar) document.getElementById('profileAvatar').src = data.avatar;
    if (data.logo) document.getElementById('siteLogo').src = data.logo;
	
	// Render Education Section
    if (data.education) {
      const educationContainer = document.getElementById('educationList');
      educationContainer.innerHTML = data.education.map(edu => `
        <div class="education-item">
          <div class="edu-header">
            <h4>${edu.degree}</h4>
            <span class="edu-year">${edu.year}</span>
          </div>
          <p class="edu-institution">${edu.institution}</p>
          <p class="edu-details">${edu.details}</p>
        </div>
      `).join('');
    }

    // Render skills
    const skillsContainer = document.getElementById('skillsList');
    skillsContainer.innerHTML = data.skills.map(s => `<li>${s}</li>`).join('');

    // Render projects
    const projectsContainer = document.getElementById('projectsGrid');
    projectsContainer.innerHTML = data.projects.map(p => `
      <div class="project-item">
        <h4>${p.title}</h4>
        <small>${p.tech}</small>
        <p>${p.desc}</p>
      </div>
    `).join('');

  } catch (err) {
    console.error('Error fetching resume API:', err);
    document.getElementById('heroName').textContent = 'Er. Rohit';
  }
}

async function handleContactSubmit(event) {
  event.preventDefault();

  const submitBtn = document.getElementById('submitBtn');
  const alertBox = document.getElementById('statusAlert');

  const formData = {
    name: document.getElementById('name').value.trim(),
    email: document.getElementById('email').value.trim(),
    message: document.getElementById('message').value.trim()
  };

  submitBtn.disabled = true;
  submitBtn.textContent = 'Submitting...';
  alertBox.className = 'alert hidden';

  try {
    const res = await fetch('api.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(formData)
    });

    const result = await res.json();

    if (!res.ok) {
      throw new Error(result.message || 'Error processing message.');
    }

    alertBox.className = 'alert alert-success';
    alertBox.textContent = result.message;
    document.getElementById('contactForm').reset();
  } catch (err) {
    alertBox.className = 'alert alert-error';
    alertBox.textContent = err.message;
  } finally {
    submitBtn.disabled = false;
    submitBtn.textContent = 'Send Message';
  }
}