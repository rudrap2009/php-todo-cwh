// Optional: Smooth page transitions or interactions
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.todo-form button').forEach(btn => {
    btn.addEventListener('mouseover', () => btn.style.transform = 'scale(1.1)');
    btn.addEventListener('mouseout', () => btn.style.transform = '');
  });
});
