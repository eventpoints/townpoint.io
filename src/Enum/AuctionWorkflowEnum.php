<?php

declare(strict_types = 1);

namespace App\Enum;

enum AuctionWorkflowEnum: string
{
    case STATE_DRAFT = 'draft';
    case STATE_PENDING_MOD_REVIEW = 'pending-moderator-review';
    case STATE_MOD_REVIEW_REJECTED = 'moderator-rejected';
    case STATE_ACCEPTING_OFFERS = 'accepting-offers';
    case STATE_PENDING_OFFER_ACCEPTANCE = 'pending-offer-acceptance';
    case STATE_PENDING_BUYER_INSPECTION = 'pending-buyer-inspection';
    case STATE_BUYER_INSPECTION_REJECTED = 'buyer-inspection-reject';
    case STATE_COMPLETE = 'complete';

    case TRANSITION_TO_MOD_REVIEW = 'to_mod_review';
    case TRANSITION_TO_MOD_REVIEW_REJECTED = 'to_mod_rejected';
    case TRANSITION_TO_ACCEPTING_OFFERS = 'to_accepting_offers';
    case TRANSITION_TO_ACCEPTING_OFFERS_AFTER_MOD_REJECTION = 'to_accepting_offers_after_mod_rejection';
    case TRANSITION_TO_PENDING_OFFER_ACCEPTANCE = 'to_pending_offer_acceptance';
    case TRANSITION_TO_PENDING_OFFER_ACCEPTANCE_AFTER_REJECTION = 'to_pending_offer_acceptance_after_rejection';
    case TRANSITION_TO_PENDING_BUYER_INSPECTION = 'to_pending_buyer_inspection';
    case TRANSITION_TO_BUYER_INSPECTION_REJECTED = 'to_buy_inspection_rejected';
    case TRANSITION_TO_COMPLETE = 'to_complete';

}
