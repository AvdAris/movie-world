document.addEventListener('DOMContentLoaded', () => {
    const newMovieBtn = document.getElementById('newMovieBtn');
    const newMoviePopup = document.getElementById('newMoviePopup');
  
    if (newMovieBtn && newMoviePopup) {
        newMovieBtn.addEventListener('click', () => {
            document.getElementById('movieTitle').value = '';
            document.getElementById('movieDescription').value = '';
            newMoviePopup.style.display = 'flex';
          });
          
      newMovieBtn.addEventListener('click', () => {
        newMoviePopup.style.display = 'flex';
      });
  
      newMoviePopup.addEventListener('click', (e) => {
        if (e.target === newMoviePopup) {
          newMoviePopup.style.display = 'none';
        }
      });
    }

    const postMovieBtn = document.getElementById('postMovieBtn');

if (postMovieBtn) {
  postMovieBtn.addEventListener('click', () => {
    const title = document.getElementById('movieTitle').value.trim();
    const description = document.getElementById('movieDescription').value.trim();

    if (!title || !description) {
      alert('Please fill in both fields.');
      return;
    }

    if (title.length > 50) {
      alert('Title must be 50 characters or less.');
      return;
    }

    fetch('/api/movies', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({ title, description })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        document.getElementById('newMoviePopup').style.display = 'none';
        alert('Movie posted!');
        window.location.reload();
      } else {
        alert(data.message || 'Failed to post movie.');
      }
    })
    .catch(err => {
      console.error('Error posting movie:', err);
      alert(err);
    });
  });
}

  });
  