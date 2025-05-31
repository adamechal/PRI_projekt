<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="html" encoding="UTF-8" indent="yes"/>

  <xsl:template match="/title">
    <html lang="cs">
      <head>
        <meta charset="UTF-8" />
        <title><xsl:value-of select="name_en"/> - AniShelf</title>
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
            <a href="index.php">Domů</a>
            <a href="anime.php">Anime</a>
            <a href="manga.php">Manga</a>
          </div>
        </nav>

        <div id="content" class="title-detail">
          <h1 class="title-en"><xsl:value-of select="name_en"/></h1>
          <h2 class="title-jp"><xsl:value-of select="name_jp"/></h2>

          <div class="title-main">
            <div class="title-image-info">
              <img class="title-image" src="images/{image}" alt="{name_en}"/>
              <div class="title-info">
                <p><strong>Typ:</strong> <xsl:value-of select="type"/></p>
                <p><strong>Status:</strong> <xsl:value-of select="status"/></p>
                <p><strong>Žánry:</strong> <xsl:value-of select="genres"/></p>
                <p><strong>Epizody:</strong> <xsl:value-of select="episodes"/></p>
              </div>
            </div>

            <div class="title-synopsis">
              <h3>Synopse</h3>
              <p><xsl:value-of select="synopsis"/></p>
            </div>
          </div>

          <hr/>

          <xsl:if test="relations/relation">
            <h3>Vztahy</h3>
            <xsl:for-each select="relations/relation">
              <a class="relation-card" href="title.php?slug={slug}">
                <div class="relation-text">
                  <strong><xsl:value-of select="type"/></strong>
                  (<xsl:value-of select="content_type"/>)
                </div>
                <div class="relation-content">
                  <img src="images/{image}" alt="{name_en}"/>
                  <div>
                    <div class="relation-title-en"><xsl:value-of select="name_en"/></div>
                    <div class="relation-title-jp"><xsl:value-of select="name_jp"/></div>
                  </div>
                </div>
              </a>
            </xsl:for-each>
          </xsl:if>
        </div>

      </body>
    </html>
  </xsl:template>
</xsl:stylesheet>
