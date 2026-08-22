# Project Presentation

`Library-Management-System-Presentation.pptx` — a 17-slide academic project-defence deck
covering the problem statement, objectives, technology stack, architecture, database
design, security model, core modules, reporting, testing and future work.

The deck is generated from code so it stays reproducible and easy to edit.

## Regenerate

```bash
pip install python-pptx
python3 presentation/build_deck.py
```

The script writes `presentation/Library-Management-System-Presentation.pptx`
(16:9, 13.333in × 7.5in).

## Slide index

| # | Slide |
|---|---|
| 1 | Title |
| 2 | Agenda |
| 3 | The Problem with Manual Library Operations |
| 4 | Project Objectives & Scope |
| 5 | Technology Stack |
| 6 | System Architecture — MVC Request Lifecycle |
| 7 | Database Design — 11 Tables |
| 8 | Entity Relationship Overview |
| 9 | Role-Based Access Control |
| 10 | Core Modules |
| 11 | Circulation Workflow — Issue to Return |
| 12 | Dashboard & Reporting |
| 13 | Implementation Highlights |
| 14 | Testing & Quality Assurance |
| 15 | Limitations & Future Work |
| 16 | Conclusion |
| 17 | Thank You / Q&A |

## Editing notes

- Colour tokens (`NAVY`, `TEAL`, `AMBER`, `CORAL`, `VIOLET`) are defined at the top of
  `build_deck.py`; change them once to re-theme the whole deck.
- Fonts: headings `Trebuchet MS`, body `Segoe UI`.
- The KPI tiles on the Dashboard slide are placeholders — replace the `"KPI"` text with
  real figures from your seeded database before presenting, or drop in screenshots.
