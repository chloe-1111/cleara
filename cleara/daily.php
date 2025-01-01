
<!-- form -->
<div class="container" id="daily-container">

<!-- eat today -->
<h1>What did you eat today?</h1>
      <input type="checkbox" id="item1" name="item1">
      <label for="item1">Chickpeas</label><br>
  
      <input type="checkbox" id="item2" name="item2">
      <label for="item2">Salmon</label><br>

      <input type="checkbox" id="item3" name="item3">
      <label for="item3">Grapefruit</label><br>
  
<!-- symptoms today -->
<h1>How was your Acne today?</h1>
  <div class="likert-scale d-flex align-items-center">
    <label><img src="images/sad.png" width="40" height="40" alt="sad icon"></label>
    <input type="radio" name="likert" value="1" id="option1">
    <label for="option1">1</label>
    <input type="radio" name="likert" value="2" id="option2">
    <label for="option2">2</label>
    <input type="radio" name="likert" value="3" id="option3">
    <label for="option3">3</label>
    <input type="radio" name="likert" value="4" id="option4">
    <label for="option4">4</label>
    <input type="radio" name="likert" value="5" id="option5">
    <label for="option5">5</label>
    <label><img src="images/smile.png" width="40" height="40" alt="smile icon"></label>
  </div>

<h1>How was your Hairloss today?</h1>
  <div class="likert-scale d-flex align-items-center">
    <label><img src="images/sad.png" width="40" height="40" alt="sad icon"></label>
    <input type="radio" name="likert2" value="12" id="option12">
    <label for="option12">1</label>
    <input type="radio" name="likert2" value="22" id="option22">
    <label for="option22">2</label>
    <input type="radio" name="likert2" value="32" id="option32">
    <label for="option32">3</label>
    <input type="radio" name="likert2" value="42" id="option42">
    <label for="option42">4</label>
    <input type="radio" name="likert2" value="52" id="option52">
    <label for="option52">5</label>
    <label><img src="images/smile.png" width="40" height="40" alt="smile icon"></label>
  </div>

<h1>Did you have your period today?</h1>
  <input type="checkbox" id="item1" name="item1">
  <label for="item1">Yes</label>
  

<!-- Submit button -->
<div class="button-right">
    <button type="submit" class="btn btn-outline-secondary">
      <a href="progress.php">Next</a>
    </button>
</div>
</div><br><br><br>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-pzjw8f+ua6U/l6s+6/79NEF9cWhvwEB8+Xs8q00m9gUZlIEa7aCP5D9oJXzmk0vo" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"  crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"  crossorigin="anonymous"></script>

</html>

<!-- Likert scale -->  
<script>
    const likertInputs = document.querySelectorAll('.likert-scale input[type="radio"]');
  
  likertInputs.forEach(input => {
    input.addEventListener('change', () => {
      console.log('Selected value:', input.value);
    });
  });
</script>


