<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@clearwell.test')->first();

        if (! $admin) {
            return;
        }

        $articles = [
            [
                'title' => 'A Beginner\'s Guide to Mindfulness Meditation',
                'body' => <<<'TXT'
Mindfulness is the practice of paying attention, on purpose, to the present moment without judgement. It sounds simple, and in a sense it is — but like most simple things, it rewards patience and repetition.

If you are brand new, start with five minutes a day. Sit somewhere comfortable where you won't be interrupted. Close your eyes, or let your gaze soften towards the floor. Bring your attention to the physical sensation of breathing — the air at your nostrils, the lift of your chest, the slight fall of your shoulders on the exhale.

Within seconds, your mind will wander. This is not a failure. It is, in fact, the whole exercise. The moment you notice that you have drifted into thought is the moment of practice. You gently acknowledge "thinking," and return to the breath. You might do this a hundred times in five minutes. That is a hundred small repetitions of the mental muscle you are here to build.

Over weeks, you may start to notice small shifts: a slightly longer pause before you react, a softer edge on a stressful afternoon, a willingness to feel something uncomfortable instead of running from it. These changes are quiet. If you are looking for fireworks, you will miss them.

Begin small. Be patient. Trust that showing up matters more than how any single session felt.
TXT,
            ],
            [
                'title' => 'The Science of Why Breathing Slowly Calms You Down',
                'body' => <<<'TXT'
When you are stressed, your sympathetic nervous system — the part responsible for fight-or-flight — takes over. Your heart rate climbs, your breathing shortens, your blood pressure rises. This is helpful if a bear is chasing you. It is much less helpful before a job interview or during a difficult conversation.

Slow, deliberate breathing is one of the very few voluntary levers you have on your autonomic nervous system. When you extend your exhale to be longer than your inhale, you stimulate the vagus nerve. The vagus nerve activates the parasympathetic branch — the "rest and digest" counterweight. Heart rate slows. Muscles soften. The body receives a message, in a language older than words, that the threat has passed.

A simple protocol to try: inhale for four seconds, exhale for six. Do this for two minutes. That is it. You do not need an app, a cushion, or a quiet room. You can do it at your desk, in traffic, or waiting for your coffee.

The benefit is not permanent calm — nothing is. The benefit is that you build a reliable way to interrupt the stress cascade before it fully takes hold. Over time, this single skill can reshape how you move through a difficult day.
TXT,
            ],
            [
                'title' => 'Understanding the Wandering Mind',
                'body' => <<<'TXT'
Researchers at Harvard once tracked thousands of people throughout their day, asking them at random moments what they were doing and what they were thinking. They found that roughly 47% of waking hours are spent thinking about something other than what the person is doing. Nearly half of life, in a sense, is spent elsewhere.

The striking finding was this: people reported being less happy when their minds wandered, regardless of what they wandered to — pleasant fantasy, neutral planning, or unpleasant rumination. Mind-wandering itself, not its content, was associated with lower wellbeing.

This is one of the strongest arguments for a meditation practice. It is not about emptying the mind or achieving a mystical state. It is about spending a greater share of your life actually inhabiting the life you are living — noticing the coffee while you drink it, hearing your child while they speak, feeling the wind on a walk instead of rehearsing an argument you will never have.

You cannot stop the mind from wandering. Brains are wandering machines. But you can train the small, repeatable skill of noticing that it has wandered and choosing, gently, to come back. That choice, made thousands of times, is the practice.
TXT,
            ],
            [
                'title' => 'Body Scan: Meeting the Sensations You Usually Ignore',
                'body' => <<<'TXT'
A body scan is a guided meditation in which you move your attention slowly through the body, part by part, noticing whatever sensations are present. It is deceptively powerful and surprisingly hard.

Lie down if you can, or sit in a supported chair. Start at the soles of your feet. Without moving, place your attention there and ask a simple question: what does this actually feel like? Warmth? Cold? Pressure? Tingling? Numbness is also an answer. So is "I cannot tell."

Move up slowly — ankles, calves, knees, thighs. Do not try to relax the body. Do not try to change anything. Your only job is to feel, accurately, what is already there.

Most of us spend the day living from the neck up, treating the body as a transport system for the brain. A body scan reverses this. It returns you to the living, sensing organism you actually are.

Many people find the practice brings up resistance — boredom, restlessness, an urge to move on quickly. This is information. The places we do not want to feel are often the places carrying the most unexamined tension. You do not have to force anything. Simply notice, and let the attention rest there a moment longer than feels comfortable.
TXT,
            ],
            [
                'title' => 'Why "Doing Nothing" Is So Hard',
                'body' => <<<'TXT'
One of the stranger discoveries of modern psychology came from a series of experiments in which participants were left alone in a room with nothing to do for fifteen minutes. They were given the option to give themselves a mild electric shock rather than sit with their own thoughts. A substantial number took the shock — some of them repeatedly.

We are not, as a species, well-practised at stillness. Our phones have filled every queue, every elevator ride, every awkward pause. The idea of sitting quietly for a quarter of an hour, with nothing to consume and nothing to produce, now strikes many adults as borderline unbearable.

Meditation, at its core, is a training in the capacity to be present with what is, including the experience of having nothing in particular to do. It is why the first weeks of practice are often uncomfortable. You are not failing. You are discovering, possibly for the first time, what your unoccupied mind actually contains.

This is why the practice matters. The capacity to tolerate your own inner life without immediately reaching for a distraction is not a minor skill. It underwrites your ability to be alone, to think clearly, to be with other people without performing, and to make decisions that are not just reactions to discomfort.
TXT,
            ],
            [
                'title' => 'Loving-Kindness Meditation: An Antidote to Self-Criticism',
                'body' => <<<'TXT'
Loving-kindness meditation, known in Pali as metta, is a practice of deliberately generating goodwill — first for yourself, then for people close to you, then for neutral people, then for someone difficult, and finally for all beings.

The traditional phrases are simple. "May I be safe. May I be happy. May I be healthy. May I live with ease." You repeat these silently, slowly, letting them land. Then you bring to mind someone you love easily — a child, a close friend, a pet — and repeat the phrases for them. "May you be safe. May you be happy..."

What surprises most beginners is how difficult the first category is. Offering yourself well-wishes, without conditions, without qualifications, without the familiar inner voice that says "yes, but first you need to..." — this can feel strange, even false.

Keep going anyway. The practice is not contingent on feeling the warmth immediately. It is a training. Over weeks, something genuinely does shift. The habitual self-critical voice loses some of its monopoly. You begin to treat yourself, in small moments, with the same basic decency you would extend to a stranger at a bus stop.

This is not self-indulgence. It turns out that people who treat themselves with kindness also have more to offer others.
TXT,
            ],
            [
                'title' => 'How to Build a Meditation Habit That Actually Sticks',
                'body' => <<<'TXT'
Most new meditators quit within the first month. The issue is almost never lack of willpower. It is almost always a setup problem.

Anchor the practice to something you already do reliably. "I will meditate after I brush my teeth in the morning" is a much more durable commitment than "I will meditate every day." The existing behaviour acts as a cue; the new behaviour becomes the reward's next step.

Keep the first sessions embarrassingly short. Two minutes. One minute, if two feels like too much. The point of the first month is not to meditate well. It is to become the kind of person who meditates at all. Duration can grow later, after the identity has stabilised.

Accept that you will miss days. Do not miss two in a row. A single missed day is a day. Two missed days is the beginning of a new pattern. When you slip, skip the self-flagellation — just sit the next morning.

Track sessions in a way that is visible. A small mark on a calendar, a streak in an app, a physical token moved from one bowl to another. The dopamine hit of marking a session is a surprisingly useful motivator during the messy middle weeks when the novelty has worn off and the benefits have not yet fully arrived.
TXT,
            ],
            [
                'title' => 'Working With Anxiety Through Meditation',
                'body' => <<<'TXT'
If you have ever tried to meditate while anxious, you already know the paradox: the very thing that might help you is the thing that anxiety makes hardest to do. Sitting still and turning inward can initially amplify the sensations you were hoping to escape.

This is not a reason to avoid the practice. It is, however, a reason to adjust it.

Start with movement-based practice when anxiety is high. A slow walk, aware of your feet landing, is a legitimate meditation. Anxious energy often needs somewhere to go before stillness is tolerable.

Use a longer exhale. Anxiety shortens and sharpens the breath; deliberately stretching the exhale signals the nervous system that the threat is over. Four counts in, six counts out, for even a couple of minutes, is meaningfully calming.

Name what is happening. "This is anxiety. This is a tightening in the chest. This is a thought about tomorrow." Naming the experience, rather than being fused with it, creates a small but real amount of space.

Above all, do not try to make the anxiety go away. That is a form of fighting it, which is a form of feeding it. The goal is not absence. The goal is a more workable relationship — being with the experience, clearly and kindly, until it shifts on its own.
TXT,
            ],
            [
                'title' => 'The Difference Between Concentration and Awareness',
                'body' => <<<'TXT'
Meditation instructions often collapse two distinct skills into a single word. It is worth pulling them apart.

Concentration is the ability to rest attention on a single object — the breath, a phrase, the flame of a candle — and return it, again and again, when it strays. Concentration is narrow and stable. A well-trained concentrative mind is like a steady hand holding a magnifying glass.

Awareness is the ability to know, in real time, what is arising in experience — a thought, a sound, a mood, a sensation — without being captured by it. Awareness is wide and fluid. It is less like holding a magnifying glass and more like the open space in which the glass, the object, and the attending mind are all noticed.

Most mature practice alternates between the two. You use concentration to stabilise the mind, because an untrained mind cannot see itself clearly. Once a basic steadiness is there, you open awareness to include whatever is present — without needing to hold on, or push away, or fix.

Beginners do well to emphasise concentration first. A few months of consistent single-object practice builds the base from which wider awareness becomes possible. Skipping this step tends to produce a vague, dreamy practice that feels nice but does not translate into the rest of life.
TXT,
            ],
            [
                'title' => 'Sleep, Stress, and the Case for an Evening Sit',
                'body' => <<<'TXT'
Many people associate meditation with mornings, and there are good reasons to begin the day with practice. But for chronic poor sleepers, there is an underrated argument for sitting in the evening.

Insomnia is rarely about tiredness. It is almost always about an activated nervous system meeting a quiet room. The work of the day has ended, the distractions have dropped away, and the unprocessed stress of the previous sixteen hours finally has the stage to itself.

A fifteen-minute evening sit, ideally an hour or two before bed, gives the nervous system a chance to downshift before the lights go out. A body scan works well here. So does a slow-breathing practice with a long exhale. The aim is not to fall asleep — that can turn the practice into another performance target — but to let the body arrive where it already is.

One caveat: if you are exhausted, meditating in bed tends to merge into sleeping, which is fine if sleep is the priority but trains the nervous system to associate the cushion with dozing off. Sit upright, in a chair if needed, then go to bed when you are done.

Over several weeks, many people find not only that sleep improves, but that the quality of the final waking hour — the hour we most often spend scrolling — becomes one they actually want back.
TXT,
            ],
            [
                'title' => 'On the Myth of a Clear Mind',
                'body' => <<<'TXT'
The single most common reason people give for not meditating is "I can't clear my mind." It is worth stating plainly: no one can. That is not the goal, and it never was.

The mind produces thoughts the way the heart produces beats. Asking it to stop is a category error. Experienced meditators do not have quieter minds. They have a different relationship with the noise.

What changes with practice is not the presence of thoughts but the identification with them. A beginner is inside the thought. A practised meditator sees the thought arise, sees it try to pull attention, and — sometimes — lets it pass without following. Sometimes they still follow, of course. But the ratio shifts.

The practical implication is that the measure of a good meditation session is not how few thoughts you had. It is how many times you noticed you had drifted and returned. Each return is a single repetition of the actual skill being trained.

If you give yourself the goal of a blank mind, you will quit within a fortnight. If you give yourself the goal of noticing and returning, you will have succeeded from the very first minute.
TXT,
            ],
            [
                'title' => 'Walking Meditation: When Sitting Isn\'t Working',
                'body' => <<<'TXT'
Not every nervous system is ready to sit still. If you are going through a particularly charged period — a grief, a burnout, a transition — the cushion can feel less like a refuge and more like a pressure cooker. Walking meditation is a quiet, effective alternative.

Find a flat stretch of ground, indoors or out, maybe ten to twenty paces long. Walk slowly — much slower than feels natural. Let your attention rest on the sensation of the feet: lifting, moving, placing. When you reach the end, turn and walk back. Do this for ten or fifteen minutes.

The body in motion is often a more tolerable object of attention than the body at rest. There is somewhere for energy to go. The mind still wanders, and you still notice and return — the core training is identical. The container is just more forgiving.

Over time, the practice generalises. A normal walk to the station becomes an opportunity to spend five minutes inside your own body rather than inside your phone. A lap of the block after a difficult meeting becomes something more than a break — it becomes a way of digesting what just happened before moving into what comes next.

Stillness is not the only gate. For some practitioners, and for almost everyone in certain seasons, walking is the door that opens.
TXT,
            ],
            [
                'title' => 'How Meditation Changes the Way You Listen',
                'body' => <<<'TXT'
One of the less-discussed benefits of a regular practice is what it does to ordinary conversations. Listening, most of the time, is not really listening. It is a performance of waiting, in which one person produces sounds while the other composes the reply they will deliver the moment the first one breathes.

Meditation trains the capacity to stay with what is present without needing to respond to it immediately. That is a description of a meditation session, but it is also a description of real listening — hearing a sentence all the way through without running it through the machinery of agreement, disagreement, and anticipation.

You will notice this shift before other people do, but eventually they notice too. Conversations become slightly longer, slightly less brittle, slightly more likely to contain something that surprises both parties. People tell you things they did not plan to tell you, because they can feel that the reply is not already loaded.

This is, quietly, one of the most useful things meditation gives you. The benefits marketed to beginners — calm, focus, better sleep — are real. But the slow upgrade to your relationships, through the unglamorous mechanism of being slightly more present while another person is speaking, may be the one that matters most.
TXT,
            ],
            [
                'title' => 'Guilt, Shame, and the Inner Critic',
                'body' => <<<'TXT'
Inside almost every adult there lives a voice that narrates all the ways they are falling short. In meditation, when the outer noise of the day subsides, this voice becomes easier to hear. For many practitioners, this is initially an unwelcome discovery. A practice that was supposed to bring peace is instead revealing an internal commentary of stunning harshness.

It helps to remember that the voice was already there. The practice did not create it; the practice turned the volume up on what was always playing. That is not a setback — it is the necessary first step. You cannot change a pattern you cannot hear.

The next move is counterintuitive. You do not argue with the voice. You do not try to replace it with affirmations. You simply observe it, with the same equanimity you bring to the sound of a passing car. "Ah — that thought again. The one about not being enough."

Identified, the voice begins to lose its monopoly. Named, it becomes one voice among many rather than the narrator of reality. You will not eliminate it, and you do not need to. You only need to demote it from the role of truth-teller to the role of recurring guest, whose arrival you can greet without having to obey.
TXT,
            ],
            [
                'title' => 'The Weekly Review: A Simple Ritual for Mental Clarity',
                'body' => <<<'TXT'
Meditation handles the inner weather of the day. A weekly review handles the drift of the week. The two are complementary, and together they form a more complete practice of attention than either does alone.

Set aside thirty quiet minutes, once a week — Friday afternoon and Sunday evening are the most common choices. Take a notebook. Do not take your phone.

Work through three questions, slowly.

What actually happened this week? Not what I meant to do, not what was on the calendar — what I genuinely spent my time on. Be honest. The gap is usually larger than expected.

What energised me, and what drained me? Write specific examples. Patterns emerge over a month of reviews that no single week reveals.

What do I want next week to be about? Pick one theme, not five. A week organised around "finish the draft" goes differently from a week organised around a to-do list of forty items.

The magic of the review is not in the answers. It is in the half-hour of undisturbed thinking itself, which most modern adults simply never do. The review is, in a quiet way, another form of meditation — a longer, language-based practice of stepping outside the current of the week to see where it is actually flowing.
TXT,
            ],
            [
                'title' => 'Meditation and the Art of Not Reacting',
                'body' => <<<'TXT'
Between a stimulus and a response there is a small gap. Most of our suffering, and a good deal of our regrettable behaviour, happens because that gap closes too fast to use.

Someone says something sharp and we are already defending. An email arrives and we are already typing. The phone buzzes and we have already picked it up. In each case, a reaction has replaced a response, and the part of us that might have chosen differently was never consulted.

Meditation trains the gap. It does not install a delay — it uncovers the one that was always there, underneath the habit of speed. Each time you sit and watch a thought arise without acting on it, you are rehearsing the same move you will need during a hard conversation at 4pm.

It will not work every time. The old reflex is strong, and the new one takes years to become reliable. But even a fractional improvement has outsized consequences. A one-second pause before sending a furious reply is often the difference between a problem solved and a relationship damaged. A single breath before answering a provocative question is often the difference between saying what you mean and saying what you will regret.

The work is quiet and the returns compound.
TXT,
            ],
            [
                'title' => 'What to Do When the Practice Feels Stale',
                'body' => <<<'TXT'
If you meditate long enough, you will hit a stretch where the practice feels dead. The techniques that used to open things up produce nothing. The sessions blur into each other. The motivation that carried you through the first year quietly leaks away.

This is normal. It is, in fact, a marker of having practised long enough to exhaust the initial novelty. What comes next is a deeper commitment, or a pause, or a change of approach.

Try a different technique. If you have done breath-focused concentration for years, experiment with open awareness, or loving-kindness, or a body scan. The underlying skill transfers; the surface change can re-engage the attention that has gone on autopilot.

Try a retreat, even a short one. A single weekend of longer sessions, held in a container away from normal life, often breaks through a plateau that months of daily twenty-minute sits could not.

Or simply keep showing up, without requiring the practice to feel like anything. The sessions that feel empty often do quiet work that only becomes visible weeks later, in a response you did not make, a mood you did not slip into, a conversation you handled better than the old version of you would have.

Dry seasons are part of the practice. They are not a sign that anything has gone wrong.
TXT,
            ],
            [
                'title' => 'Gratitude Without the Sentimentality',
                'body' => <<<'TXT'
Gratitude has become a wellness cliche, which is unfortunate, because the underlying practice is quietly one of the most powerful tools in the kit. Stripped of its greeting-card packaging, it is simply a discipline of noticing what is working in a life that the mind, left to itself, will catalogue mostly in terms of what is not.

The brain has a negativity bias for evolutionary reasons. Our ancestors who remembered the berry that made them sick lived longer than the ones who remembered the berries that tasted fine. We are the descendants of the worriers. This is useful when the stakes are survival. It is less useful when the stakes are whether you enjoyed your Tuesday.

A gratitude practice is a deliberate correction to this bias. It does not ignore the negative. It simply adds back in the data the brain is under-weighting. A short evening list — three specific things, not generalities — is enough. Not "my family" but "the way my daughter laughed at her own joke tonight." Not "my health" but "the walk home without pain in my knee."

Over months, the practice does not make problems disappear. It changes the ratio at which good and bad experience register. The same week, lived with and without the practice, is a different week.
TXT,
            ],
            [
                'title' => 'The Long Arc: What Ten Years of Practice Actually Looks Like',
                'body' => <<<'TXT'
There is very little honest writing about what a long meditation practice looks like from the inside, partly because people who have been at it for a decade tend to become suspicious of dramatic descriptions. The real answer is less cinematic than the beginner hopes and more interesting than they expect.

Year one: a lot of restlessness, a lot of quitting and restarting, occasional glimpses of why it might be worth continuing.

Years two and three: the practice becomes a habit. The drama decreases. The sessions are less interesting, which is sometimes the point. You notice, in small ways, that you react differently to things that used to catch you.

Years four through six: the practice starts to affect the shape of your life rather than just your sessions. You make different choices about work, relationships, and what you consume. You are less entertained by your own complaints.

Beyond that, most long-term practitioners describe something harder to summarise. Not enlightenment in any big sense. More a slow softening of the demand that life be different from how it is, paired with a clearer willingness to act where action is possible. Less static. More availability.

The ten-year version of you is not a calmer version of the current you. It is a differently-organised you, shaped by ten thousand small acts of returning your attention to where it actually is. That person is worth becoming. But you only get there one morning at a time.
TXT,
            ],
        ];

        foreach ($articles as $article) {
            Post::updateOrCreate(
                ['title' => $article['title']],
                [
                    'user_id' => $admin->id,
                    'body' => $article['body'],
                ]
            );
        }
    }
}
