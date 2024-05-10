<?php

namespace App\Service\RandomService;

use App\Service\RandomService\Contract\RandomGeneratorInterface;

class RandomQuoteService implements RandomGeneratorInterface
{
    public function generate(): string
    {
        $quotes = [
            'write, write, write, I tell you. Till your heart is content and full of warmth',
            'let us build a better world together, start by talking to each other',
            'In this age of digital communication, let us not forget the joy of receiving a heartfelt letter, written with care and sincerity.',
            'Words have the power to bridge chasms and heal wounds. Let us wield them with wisdom and compassion.',
            'A letter is a silent conversation between souls, bridging distances and forging connections.',
            'When you write a letter, you pour a piece of your soul onto the page, creating a timeless treasure for the recipient.',
            'Dialogue is the cornerstone of understanding. Let us engage in conversations that enlighten and enrich our lives.',
            'In a world buzzing with noise, the silence of a letter holds a profound resonance.',
            'The art of letter writing is not just about words on paper; it is about weaving emotions into tangible expressions of care and affection.',
            'Through letters, we transcend the limitations of time and space, reaching out to touch hearts across the miles.',
            'Communication is not merely exchanging words; it is about listening with an open heart and speaking with genuine intent.',
            'In a letter, every word carries the weight of sincerity, every sentence echoes the melody of the soul.',
            'Let us not underestimate the power of a single letter to brighten someone\'s day or mend a broken spirit.',
            'The beauty of letters lies in their ability to capture the essence of a moment and preserve it for eternity.',
            'Writing letters is an act of love, a testament to the enduring bonds that connect us to one another.',
            'In the digital age, handwritten letters are like rare gems, cherished for their authenticity and intimacy.',
            'When we write letters, we are not just communicating; we are creating a legacy of love and memories.',
            'In the quiet solitude of writing, we discover the depth of our thoughts and the richness of our emotions.',
            'Every letter is a journey, a voyage of words traveling from one heart to another.',
            'Let us be architects of empathy, building bridges of understanding through the simple yet profound act of writing letters.',
        ];

        return $quotes[array_rand($quotes)];
    }
}
