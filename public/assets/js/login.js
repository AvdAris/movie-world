document.addEventListener('DOMContentLoaded', () => {
  const container = document.getElementById('movie-list');
  const template = document.getElementById('movie-template');
  const countEl = document.getElementById('movie-count');
  let currentSort = localStorage.getItem('sortBy') || 'date';
  let currentDirection = localStorage.getItem('sortDir') || 'desc';
  fetchMovies(currentSort, currentDirection); 
  updateSortButtonUI(currentSort, currentDirection);

  document.querySelectorAll('.sort-btn').forEach(button => {
    button.addEventListener('click', () => {
      const sortType = button.dataset.sort;
      let direction = button.dataset.direction || 'desc';

      direction = (direction === 'desc') ? 'asc' : 'desc';
      button.dataset.direction = direction;

      localStorage.setItem('sortBy', sortType);
      localStorage.setItem('sortDir', direction);

      currentSort = sortType;
      currentDirection = direction;

      updateSortButtonUI(sortType, direction);
      fetchMovies(sortType, direction);
    });
  });
  
  function updateSortButtonUI(activeSort, activeDir) {
    document.querySelectorAll('.sort-btn').forEach(btn => {
      const label = btn.dataset.sort.charAt(0).toUpperCase() + btn.dataset.sort.slice(1);
      const icon = (btn.dataset.sort === activeSort)
        ? (activeDir === 'asc' ? '🔼' : '🔽')
        : '';
      btn.textContent = `${label} ${icon}`;
      btn.dataset.direction = (btn.dataset.sort === activeSort) ? activeDir : 'desc';
    });
  }

  function fetchMovies(sort = 'date', direction = 'desc') {
    fetch(`/api/movies?sort=${sort}&direction=${direction}`)
    .then(res => res.json())
    .then(movies => {
      container.innerHTML = '';
      countEl.textContent = `Found ${movies.length} movies`;
      movies.forEach(movie => {
        const clone = template.content.cloneNode(true);
        clone.querySelector('.title').textContent = movie.title;
        clone.querySelector('.date').textContent = formatDate(movie.publication_date);
        clone.querySelector('.movie-description').textContent = movie.description;
        clone.querySelector('.likes').textContent = `${movie.likes_count ?? 0} likes | ${movie.hates_count ?? 0} hates`;
        const posterLink = clone.querySelector('.poster-link');

        if (loggedInUser && movie.user_id === loggedInUser) {
          posterLink.textContent = "You";
        } else if(movie.poster_name){
          posterLink.textContent = movie.poster_name;
        } else {
          posterLink.textContent = "Unknown";
        }
        
        const likeBtn = clone.querySelector('.like-btn');
        const hateBtn = clone.querySelector('.hate-btn');
        const likesCountEl = clone.querySelector('.likes');
        const voteButtons = clone.querySelector('.vote-buttons');
        
        if (!loggedInUser || movie.user_id === loggedInUser) {
          voteButtons.classList.add('hidden');
        } else {
          if (movie.user_vote === 'like') {
            likeBtn.classList.add('active');
          } else if (movie.user_vote === 'hate') {
            hateBtn.classList.add('active');
          }

          likeBtn.addEventListener('click', () => handleVote(movie.id, 'like', likeBtn, hateBtn, likesCountEl));
          hateBtn.addEventListener('click', () => handleVote(movie.id, 'hate', likeBtn, hateBtn, likesCountEl));
        }


        container.appendChild(clone);
      });
    })
    .catch(err => {
      console.error("Failed to load movies:", err);
      countEl.textContent = "Failed to load movies.";
    });
  }
  function formatDate(dateStr) {
    const date = new Date(dateStr);
    return date.toLocaleDateString('en-GB');
  }

  function handleVote(movieId, voteType, likeBtn, hateBtn, likesCountEl) {
    const isLikeActive = likeBtn.classList.contains('active');
    const isHateActive = hateBtn.classList.contains('active');
  
    fetch('/api/votes', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ movie_id: movieId, vote: voteType })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        const match = likesCountEl.textContent.match(/(\d+)\s+likes\s+\|\s+(\d+)\s+hates/);
        let likes = parseInt(match[1], 10);
        let hates = parseInt(match[2], 10);
  
        likeBtn.classList.remove('active');
        hateBtn.classList.remove('active');
  
        if (voteType === 'like') {
          if (isLikeActive) {
            likes--;
          } else {
            likes++;
            if (isHateActive) hates--;
            likeBtn.classList.add('active');
          }
        } else if (voteType === 'hate') {
          if (isHateActive) {
            hates--;
          } else {
            hates++;
            if (isLikeActive) likes--;
            hateBtn.classList.add('active');
          }
        }
        likesCountEl.textContent = `${likes} likes | ${hates} hates`;
  
      } else {
        alert(data.message || 'Voting failed');
      }
    })
    .catch(err => {
      console.error('Vote error:', err);
      alert('Something went wrong.');
    });
  }
});
