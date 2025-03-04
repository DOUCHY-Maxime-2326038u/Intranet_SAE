<h1>FAQ - Posez votre question</h1>

<!-- Formulaire de soumission de question -->
<div class="faq-form">
    <form method="post" action="?ctrl=Question&action=ajouter">
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token ?? null; ?>">
        <textarea name="question" placeholder="Entrez votre question ici" rows="4" required></textarea>
        <br>
        <button type="submit">Envoyer</button>
    </form>
</div>

<!-- Affichage des questions en cards -->
<div class="faq-list">
    <?php if(isset($questions) && count($questions) > 0): ?>
        <?php foreach($questions as $question): ?>
            <div class="faq-card" onclick="toggleAnswer(this)">
                <div class="faq-question">
                    <strong>Question :</strong> <?php echo htmlspecialchars($question['CONTENU']); ?>
                </div>
                <?php if(!empty($question['CONTENU'])): ?>
                    <div class="faq-answer">
                        <strong>Réponse :</strong> <?php echo nl2br(htmlspecialchars($question['REPONSE'])); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Aucune question publiée pour le moment.</p>
    <?php endif; ?>
</div>

<script>
    // Fonction pour afficher/masquer la réponse au clic sur la card
    function toggleAnswer(card) {
        var answer = card.querySelector('.faq-answer');
        if(answer) {
            answer.style.display = (answer.style.display === "block") ? "none" : "block";
        }
    }
</script>