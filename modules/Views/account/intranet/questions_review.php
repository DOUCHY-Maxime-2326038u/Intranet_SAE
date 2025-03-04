<div class="questions-review-container">
    <h1>Revue des Questions</h1>
    <?php if (isset($questions) && count($questions) > 0): ?>
        <?php foreach ($questions as $question): ?>
            <form method="post" action="?ctrl=Intranet&action=majQuestion">
                <input type="hidden" name="id" value="<?php echo $question['ID']; ?>">
                <div class="question-card">
                    <p><strong>Question :</strong> <?php echo htmlspecialchars($question['CONTENU']); ?></p>
                    <label for="answer_<?php echo $question['ID']; ?>">Réponse :</label>
                    <textarea id="answer_<?php echo $question['ID']; ?>" name="answer" rows="3"></textarea>
                    <label>
                        <input type="checkbox" name="is_publiee" value="1" <?php echo ($question['EST_PUBLIEE'] == 1) ? 'checked' : ''; ?>>
                        Publier cette question
                    </label>
                    <button type="submit">Enregistrer</button>
                </div>
            </form>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune question à réviser.</p>
    <?php endif; ?>
</div>