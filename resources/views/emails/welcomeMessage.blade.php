<x-mail::message>
# Welcome to {{ config('app.name') }}, {{ $user->username }}! 🎉

We're thrilled to have you join our community. Here's what you can do to get started:

<x-mail::panel>
**Complete Your Profile:** Add a profile picture and bio to let others know more about you.

**Explore Content:** Discover amazing content from other community members.

**Connect:** Follow users who share your interests and build your network.

**Share:** Start sharing your own content and engage with the community.
</x-mail::panel>

<x-mail::button :url="url('/')">
Get Started Now
</x-mail::button>

If you have any questions or need help getting started, don't hesitate to contact our support team.

Thanks,<br>
The {{ config('app.name') }} Team
</x-mail::message>
