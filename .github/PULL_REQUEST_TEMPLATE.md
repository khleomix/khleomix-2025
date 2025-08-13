# Closes

<!--
REQUIRED. Link the JIRA ticket this PR addresses:
[#WDSBT-123](https://app.clickup.com/t/9011385391/WDSBT-123)

For multiple tickets use:
- [#WDSBT-123](https://app.clickup.com/t/9011385391/WDSBT-123)
- [#WDSBT-124](https://app.clickup.com/t/9011385391/WDSBT-124)
-->

## Summary

<!--
Provide a clear, concise summary of the changes. Use bullet points for clarity:
- What problem does this solve?
- What approach did you take?
- Why this approach?
-->


<details>
<summary><h2>Environment Links</h2></summary>

<!--
REQUIRED. Link ALL relevant environments affected by this PR:
- [Development](https://dev.wdslab.com/)
- [Staging](https://staging.wdslab.com/)
- [Production](https://www.wdslab.com/)
-->
</details>

<details>
<summary><h2>Breaking Changes</h2></summary>

<!--
List any breaking changes and migration steps:
- API changes
- Database schema updates
- Configuration changes
- Required version updates
-->
</details>

<details>
<summary><h2>Post-Deployment Steps</h2></summary>

<!--
List ALL required post-deployment actions with exact commands:
```bash
# Update database schema
wp db query < migrations/WDS-123-add-new-table.sql

# Clear caches
wp cache flush
wp redis flush

# Reindex search
wp elasticpress index --setup
```
-->
</details>

<details>
<summary><h2>Testing Instructions</h2></summary>

<!--
Provide step-by-step testing instructions:
1. Prerequisites (if any)
2. Setup steps
3. Test scenarios
4. Expected results
5. Edge cases to verify

### Test Scenarios
- [ ] Scenario 1: Description
- [ ] Scenario 2: Description
- [ ] Edge Case 1: Description
-->
</details>

<details>
<summary><h2>Visual Changes</h2></summary>

<!--
For UI changes, provide:
- Before/After screenshots
- Mobile/Desktop views
- Recordings for complex interactions
- Loom link: https://www.loom.com/screen-recorder
-->
</details>

<details>
<summary><h2>Documentation</h2></summary>

- [ ] README.md updated
- [ ] Inline code documentation added
- [ ] [Clickup Documentation](https://app.clickup.com/9011385391/v/dc/8chxn1f-5551/8chxn1f-12391) updated
- [ ] No documentation needed
</details>

<details>
<summary><h2>Quality Checks</h2></summary>

### Development
- [ ] Code follows WordPress coding standards
- [ ] ESLint/PHPCS checks pass
- [ ] New functions/methods are documented
- [ ] Error handling implemented
- [ ] Logging added where appropriate

### Testing
- [ ] Unit tests added/updated
- [ ] Integration tests added/updated
- [ ] Manual testing completed
- [ ] No tests needed

### Security
- [ ] Security best practices followed
- [ ] Input validation implemented
- [ ] Output escaping verified
- [ ] SQL queries sanitized
- [ ] Permissions checked

### Performance
- [ ] Database queries optimized
- [ ] Assets optimized
- [ ] Caching implemented where appropriate
- [ ] No performance impact

### Accessibility
- [ ] WCAG 2.1 guidelines followed
- [ ] Keyboard navigation works
- [ ] Screen reader testing done
- [ ] Color contrast verified
- [ ] `npm run a11y` passes
</details>

<details>
<summary><h2>Development Tools</h2></summary>

- [ ] 🤖 This project was developed with the help of a LLM/AI such as Cursor, Gemini, etc.
- [ ] 🔧 Custom tools/scripts created
- [ ] 📦 New build process changes
</details>

<details>
<summary><h2>Reviewer Guidelines</h2></summary>

### Code Review
- [ ] Code follows project standards
- [ ] Error handling is robust
- [ ] Security measures are adequate
- [ ] Performance impact is acceptable
- [ ] Documentation is complete

### Functional Review
- [ ] All test scenarios pass
- [ ] Edge cases handled
- [ ] User experience is smooth
- [ ] Performance is acceptable

### Technical Review
- [ ] Architecture is sound
- [ ] Dependencies are appropriate
- [ ] Database changes are safe
- [ ] Deployment steps are clear
</details>

<details>
<summary><h2>Additional Notes</h2></summary>

<!--
Include any other relevant information:
- Known limitations
- Future improvements planned
- Related PRs or tickets
- Special considerations
-->
</details>
