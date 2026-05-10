/**
 * SeoAnalyzer.js
 * Nexus Enterprise SEO Analysis Engine - Conflict-free Version
 */

const NexusSeo = {
    rules: {
        title: { min: 50, max: 60, weight: 30 },
        description: { min: 120, max: 160, weight: 30 },
        keywordInTitle: { weight: 20 },
        keywordInDescription: { weight: 20 }
    },

    analyze: function(data) {
        const { title, description, focusKeyword } = data;
        let score = 0;
        let checks = [];

        // 1. Title Length
        const titleLen = (title || "").length;
        if (titleLen >= this.rules.title.min && titleLen <= this.rules.title.max) {
            score += this.rules.title.weight;
            checks.push({ status: 'pass', message: 'Title length is perfect.' });
        } else if (titleLen === 0) {
            checks.push({ status: 'fail', message: 'Title is missing.' });
        } else {
            checks.push({ status: 'warning', message: `Title length (${titleLen}) should be 50-60 chars.` });
        }

        // 2. Description Length
        const descLen = (description || "").length;
        if (descLen >= this.rules.description.min && descLen <= this.rules.description.max) {
            score += this.rules.description.weight;
            checks.push({ status: 'pass', message: 'Description length is perfect.' });
        } else if (descLen === 0) {
            checks.push({ status: 'fail', message: 'Description is missing.' });
        } else {
            checks.push({ status: 'warning', message: `Description length (${descLen}) should be 120-160 chars.` });
        }

        if (focusKeyword && focusKeyword.trim() !== '') {
            const kw = focusKeyword.toLowerCase();
            
            // 3. Keyword in Title
            if ((title || "").toLowerCase().includes(kw)) {
                score += this.rules.keywordInTitle.weight;
                checks.push({ status: 'pass', message: 'Focus keyword found in Title.' });
            } else {
                checks.push({ status: 'fail', message: 'Focus keyword missing from Title.' });
            }

            // 4. Keyword in Description
            if ((description || "").toLowerCase().includes(kw)) {
                score += this.rules.keywordInDescription.weight;
                checks.push({ status: 'pass', message: 'Focus keyword found in Description.' });
            } else {
                checks.push({ status: 'fail', message: 'Focus keyword missing from Description.' });
            }
        } else {
            checks.push({ status: 'warning', message: 'No focus keyword set for analysis.' });
        }

        return {
            score: Math.min(100, score),
            checks: checks
        };
    }
};

// Expose both for compatibility
window.NexusSeo = NexusSeo;
window.SeoAnalyzer = NexusSeo;
