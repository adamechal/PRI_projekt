<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/titles">
    <html lang="cs">
      <head>
        <meta charset="UTF-8"/>
        <title>AniShelf - Seznam titulů</title>
        <link rel="stylesheet" href="styles.css"/>
      </head>
      <body>

        <div id="logo">
          <a href="index.php">
            <img src="images/anishelf_logo.png" width="200" height="200" alt="AniShelf logo"/>
          </a>
        </div>

        <nav>
          <div class="nav-inner">
            <div class="nav-links">
              <a href="index.php">Home</a>
              <a href="anime.php">Anime</a>
              <a href="manga.php">Manga</a>
            </div>
            <div class="nav-search">
              <input type="text" id="search" placeholder="Search name..."/>
              <select id="typeFilter">
                <option value="">All types</option>
                <option value="tv">TV</option>
                <option value="movie">Movie</option>
                <option value="manga">Manga</option>
                <option value="ln">LN</option>
              </select>
            </div>
          </div>
        </nav>

        <div id="content">
          <h1>List of all titles</h1><br/>
          <h2 class="section-title">Anime</h2>
          <xsl:for-each select="title[@category='anime']">
            <div class="title-card" data-type="{type}">
              <img src="images/{image}" alt="{name}"/>
              <div class="title-info">
                <a href="title.php?slug={slug}">
                  <xsl:value-of select="name"/>
                </a><br/>
                <div class="details">
                  <xsl:value-of select="type"/> (<xsl:value-of select="episodes"/> eps)
                </div>
              </div>
            </div>
          </xsl:for-each>

          <h2 class="section-title">Manga</h2>
          <xsl:for-each select="title[@category='manga']">
            <div class="title-card" data-type="{type}">
              <img src="images/{image}" alt="{name}"/>
              <div class="title-info">
                <a href="manga_ln_title.php?slug={slug}">
                  <xsl:value-of select="name"/>
                </a><br/>
                <div class="details">
                  <xsl:value-of select="type"/>
                </div>
              </div>
            </div>
          </xsl:for-each>
        </div>

        <script src="index.js"></script>
      </body>
    </html>
  </xsl:template>

</xsl:stylesheet>
