<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/titles">
    <html lang="cs">
      <head>
        <meta charset="UTF-8"/>
        <title>AniShelf - Anime</title>
        <link rel="stylesheet" href="styles.css" type="text/css"/>
      </head>
      <body>

        <div id="logo">
          <a href="index.php"><img src="images/anishelf_logo.png" width="200" height="200" alt="AniShelf logo"/></a>
        </div>

        <nav>
          <div class="nav-inner">
            <div class="nav-links">
              <a href="index.php">Domů</a>
              <a href="anime.php">Anime</a>
              <a href="manga.php">Manga</a>
            </div>
            <div class="nav-search">
              <input type="text" id="search" placeholder="Hledat název..."/>
            </div>
          </div>
        </nav>

        <div id="content">
          <h1>Anime a filmy</h1>

          <xsl:for-each select="title[@category='anime']">
            <div class="title-card">
              <img src="images/{image}" alt="{name}"/>
              <div class="title-info">
                <a href="title.php?slug={slug}"><xsl:value-of select="name"/></a><br/>
                <div class="details"><xsl:value-of select="type"/> (<xsl:value-of select="episodes"/> eps)</div>
              </div>
            </div>
          </xsl:for-each>
        </div>

        <script>
          <![CDATA[
            const searchInput = document.getElementById("search");
            searchInput.addEventListener("input", function () {
              const query = this.value.toLowerCase();
              document.querySelectorAll(".title-card").forEach(card => {
                const name = card.querySelector("a").textContent.toLowerCase();
                card.style.display = name.includes(query) ? "" : "none";
              });
            });
          ]]>
        </script>

      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
