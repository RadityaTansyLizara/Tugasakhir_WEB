<?= $this->renderSection("content") ?>
            </section>
            <aside id="sidebar">
                <?= view_cell("App\\Cells\\ArticleLatest::render", [
                    "category" => $_GET["category"] ?? null,
                ]) ?>
                <div class="widget-box">
    <h3 class="title">Widget Header:</h3>
    <ul>
      <li><a href="#">href Link</a></li>
      <li><a href="#">href Link</a></li>
    </ul>
  </div>
  <div class="widget-box">
    <h3 class="title">Bio:</h3>
    <p>Ignorance brings peace, but wisdom bears weight.</p>
  </div>
</aside></section><footer><p>&copy; 2025 - Tansy.</p></footer></div></body></html>