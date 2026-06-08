from pathlib import Path
import re


ROOT = Path(__file__).resolve().parents[1]
SRC = ROOT / "Rapport_Smart_Tasks_Planner.md"
OUT = ROOT / "Rapport_Smart_Tasks_Planner.tex"


SPECIALS = {
    "\\": r"\textbackslash{}",
    "&": r"\&",
    "%": r"\%",
    "$": r"\$",
    "#": r"\#",
    "_": r"\_",
    "{": r"\{",
    "}": r"\}",
    "~": r"\textasciitilde{}",
    "^": r"\textasciicircum{}",
}


def escape_plain(text: str) -> str:
    return "".join(SPECIALS.get(ch, ch) for ch in text)


def esc(text: str) -> str:
    text = re.sub(r"\[([^\]]+)\]\([^\)]+\)", r"\1", text)
    chunks = []
    pos = 0
    for match in re.finditer(r"\*\*(.+?)\*\*", text):
        chunks.append(escape_plain(text[pos:match.start()]))
        chunks.append(r"\textbf{" + escape_plain(match.group(1)) + "}")
        pos = match.end()
    chunks.append(escape_plain(text[pos:]))
    return "".join(chunks)


def is_table_line(line: str) -> bool:
    stripped = line.strip()
    return stripped.startswith("|") and stripped.endswith("|") and stripped.count("|") >= 2


def is_separator(line: str) -> bool:
    cells = [cell.strip() for cell in line.strip().strip("|").split("|")]
    return bool(cells) and all(re.fullmatch(r":?-{3,}:?", cell or "") for cell in cells)


def parse_table(lines: list[str]) -> str:
    rows = []
    for line in lines:
        if is_separator(line):
            continue
        rows.append([cell.strip() for cell in line.strip().strip("|").split("|")])

    if not rows:
        return ""

    cols = max(len(row) for row in rows)
    for row in rows:
        row.extend([""] * (cols - len(row)))

    col_width = 0.92 / cols
    spec = "|" + "|".join([f"p{{{col_width:.2f}\\textwidth}}" for _ in range(cols)]) + "|"
    output = [r"\begin{longtable}{" + spec + "}", r"\hline"]

    for index, row in enumerate(rows):
        if index == 0:
            header = " & ".join(r"\textbf{" + esc(cell) + "}" for cell in row) + r" \\ \hline"
            output.extend([header, r"\endfirsthead", r"\hline", header, r"\endhead"])
        else:
            output.append(" & ".join(esc(cell) for cell in row) + r" \\ \hline")

    output.append(r"\end{longtable}")
    return "\n".join(output)


def convert_markdown_body(markdown: str) -> str:
    start = markdown.find("# 1. Remerciements")
    body = markdown[start:] if start != -1 else markdown

    latex: list[str] = []
    table_lines: list[str] = []
    code_lines: list[str] = []
    in_code = False

    heading_map = {
        1: "chapter",
        2: "section",
        3: "subsection",
        4: "subsubsection",
    }

    def flush_table() -> None:
        nonlocal table_lines
        if table_lines:
            latex.append(parse_table(table_lines))
            latex.append("")
            table_lines = []

    def flush_code() -> None:
        nonlocal code_lines
        if code_lines:
            latex.append(r"\begin{lstlisting}")
            latex.extend(code_lines)
            latex.append(r"\end{lstlisting}")
            latex.append("")
            code_lines = []

    for raw_line in body.splitlines():
        line = raw_line.rstrip("\n")
        stripped = line.strip()

        if stripped.startswith("```"):
            flush_table()
            if in_code:
                flush_code()
                in_code = False
            else:
                in_code = True
                code_lines = []
            continue

        if in_code:
            code_lines.append(line)
            continue

        if is_table_line(line):
            table_lines.append(line)
            continue

        flush_table()

        if not stripped:
            latex.append("")
            continue

        if stripped == "---":
            latex.append(r"\bigskip\hrule\bigskip")
            continue

        heading = re.match(r"^(#{1,6})\s+(.*)$", stripped)
        if heading:
            level = len(heading.group(1))
            title = heading.group(2).strip()
            command = heading_map.get(level, "paragraph")
            latex.append("\\" + command + "{" + esc(title) + "}")
            continue

        bullet = re.match(r"^[-*]\s+(.*)$", stripped)
        if bullet:
            latex.append(r"\begin{itemize}[leftmargin=1.1cm]")
            latex.append(r"\item " + esc(bullet.group(1)))
            latex.append(r"\end{itemize}")
            continue

        latex.append(esc(stripped))
        latex.append("")

    flush_table()
    flush_code()

    content = "\n".join(latex)
    return content.replace(
        "\\end{itemize}\n\\begin{itemize}[leftmargin=1.1cm]\n",
        "",
    )


PREAMBLE = r"""
\documentclass[12pt,a4paper]{report}

% ===================== Packages =====================
\usepackage[utf8]{inputenc}
\usepackage[T1]{fontenc}
\usepackage[french]{babel}
\usepackage{lmodern}
\usepackage{geometry}
\usepackage{xcolor}
\usepackage{graphicx}
\usepackage{setspace}
\usepackage{hyperref}
\usepackage{fancyhdr}
\usepackage{titlesec}
\usepackage{titletoc}
\usepackage{enumitem}
\usepackage{array}
\usepackage{longtable}
\usepackage{booktabs}
\usepackage{colortbl}
\usepackage{tcolorbox}
\usepackage{listings}
\usepackage{float}
\usepackage{microtype}

% ===================== Mise en page =====================
\geometry{left=2.5cm,right=2.5cm,top=2.4cm,bottom=2.4cm}
\onehalfspacing
\setlength{\parindent}{0pt}
\setlength{\parskip}{6pt}
\renewcommand{\arraystretch}{1.25}
\setlength{\LTpre}{8pt}
\setlength{\LTpost}{8pt}

% ===================== Couleurs =====================
\definecolor{Primary}{HTML}{1E3A5F}
\definecolor{Accent}{HTML}{0F766E}
\definecolor{SoftBlue}{HTML}{EAF2F8}
\definecolor{SoftGray}{HTML}{F5F7FA}
\definecolor{DarkGray}{HTML}{334155}
\definecolor{CodeBg}{HTML}{F8FAFC}
\definecolor{CodeFrame}{HTML}{CBD5E1}

% ===================== Hyperliens =====================
\hypersetup{
    colorlinks=true,
    linkcolor=Primary,
    urlcolor=Accent,
    citecolor=Accent,
    pdftitle={Rapport de Projet - Smart Tasks Planner},
    pdfauthor={Ayoub CHAYB ANNOU, Hiba JANA}
}

% ===================== En-têtes et pieds de page =====================
\pagestyle{fancy}
\fancyhf{}
\lhead{\textcolor{Primary}{\textbf{Smart Tasks Planner}}}
\rhead{\textcolor{DarkGray}{Développement Backend}}
\cfoot{\textcolor{DarkGray}{\thepage}}
\renewcommand{\headrulewidth}{0.4pt}
\renewcommand{\footrulewidth}{0pt}

% ===================== Titres =====================
\titleformat{\chapter}[display]
  {\normalfont\huge\bfseries\color{Primary}}
  {\filleft\Large\chaptertitlename\ \thechapter}
  {1ex}
  {\titlerule\vspace{1ex}\filright}
  [\vspace{1ex}\titlerule]

\titleformat{\section}
  {\normalfont\Large\bfseries\color{Primary}}
  {\thesection}{0.8em}{}

\titleformat{\subsection}
  {\normalfont\large\bfseries\color{Accent}}
  {\thesubsection}{0.8em}{}

\titleformat{\subsubsection}
  {\normalfont\normalsize\bfseries\color{DarkGray}}
  {\thesubsubsection}{0.8em}{}

% ===================== Table des matières =====================
\contentsmargin{0cm}
\titlecontents{chapter}[0em]
  {\addvspace{8pt}\bfseries\color{Primary}}
  {\contentslabel{2.2em}}
  {}
  {\titlerule*[0.6pc]{.}\contentspage}

% ===================== Tableaux =====================
\arrayrulecolor{Primary}
\newcolumntype{L}[1]{>{\raggedright\arraybackslash}p{#1}}

% ===================== Code =====================
\lstdefinestyle{academiccode}{
    backgroundcolor=\color{CodeBg},
    basicstyle=\ttfamily\small,
    frame=single,
    rulecolor=\color{CodeFrame},
    breaklines=true,
    columns=fullflexible,
    keepspaces=true,
    showstringspaces=false,
    tabsize=2
}
\lstset{style=academiccode}

% ===================== Encadrés =====================
\newtcolorbox{infobox}{
    colback=SoftBlue,
    colframe=Primary,
    arc=2mm,
    boxrule=0.6pt,
    left=8pt,
    right=8pt,
    top=8pt,
    bottom=8pt
}

% ===================== Document =====================
\begin{document}

% ===================== Page de garde =====================
\begin{titlepage}
    \thispagestyle{empty}
    \begin{center}
        \vspace*{0.2cm}
        {\Large\bfseries\color{Primary} FSTG}\\[0.3cm]
        \fbox{\begin{minipage}[c][2.5cm][c]{4.8cm}
            \centering\textcolor{DarkGray}{Emplacement du logo}
        \end{minipage}}\\[1.5cm]

        {\Huge\bfseries\color{Primary} Rapport de Projet}\\[0.4cm]
        {\Large Module : Développement Backend}\\[0.2cm]
        {\Large Année Universitaire : 2025/2026}\\[1.2cm]

        \begin{tcolorbox}[colback=SoftBlue,colframe=Primary,width=0.86\textwidth,arc=2mm,boxrule=0.8pt]
            \centering
            {\LARGE\bfseries\color{Primary} Smart Tasks Planner}\\[0.2cm]
            {\large Plateforme web de productivité académique développée avec Laravel}
        \end{tcolorbox}

        \vfill
        \begin{minipage}{0.45\textwidth}
            \raggedright
            {\large\bfseries Réalisé par :}\\[0.25cm]
            Ayoub CHAYB ANNOU\\
            Hiba JANA
        \end{minipage}
        \hfill
        \begin{minipage}{0.45\textwidth}
            \raggedleft
            {\large\bfseries Encadrante :}\\[0.25cm]
            Sara Qassimi
        \end{minipage}

        \vfill
        {\color{DarkGray}\today}
    \end{center}
\end{titlepage}

\pagenumbering{roman}
\tableofcontents
\clearpage
\pagenumbering{arabic}
"""


ENDING = r"""
\end{document}
"""


def main() -> None:
    markdown = SRC.read_text(encoding="utf-8")
    latex = PREAMBLE + convert_markdown_body(markdown) + ENDING
    OUT.write_text(latex, encoding="utf-8")
    print(OUT)
    print(f"lines: {len(latex.splitlines())}")


if __name__ == "__main__":
    main()
