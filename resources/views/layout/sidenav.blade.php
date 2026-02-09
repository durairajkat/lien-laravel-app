<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ Auth::check() && !is_null(Auth::user()->details) && !is_null(Auth::user()->details->image) ? url('/') . '/image_logo/avatar/thumbnails/' . Auth::user()->details->image : url('/') . '/images/avatar5.png' }}"
                    class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p>{{ ucwords(Auth::user()->name) }}</p>
                <a href="javascript:void(0);"><i class="fa fa-circle text-success"></i>
                    {{ ucwords(Auth::user()->role_type->type) }}</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                </a>
            </li>
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-user"></i>
                    <span>Members</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu member-menu">
                    <li><a href="{{ route('user.member') }}"><i class="fa fa-circle-o"></i> Members</a></li>
                    <li><a href="{{ route('user.sub') }}"><i class="fa fa-circle-o"></i> Sub user</a></li>
                </ul>
            </li>
            <li>
                <a href="{{ route('lien.list') }}">
                    <i class="fa fa-briefcase"></i> <span>Lien Providers</span>
                </a>
            </li>
            @if (Auth::user()->role == 1)
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-list"></i>
                        <span>Project Management</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li>
                            <a href="{{ route('project.management') }}"><i class="fa fa-circle-o"></i>Roles &
                                Types</a>
                        </li>
                        <li>
                            <a href="{{ route('app.state') }}"><i class="fa fa-flag-o"></i> State</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.customer') }}"><i class="fa fa-circle-o"></i>Customers</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.remedy') }}"><i class="fa fa-circle-o"></i>Remedies</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.remedy_dates') }}"><i class="fa fa-circle-o"></i>Remedy
                                Dates</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.remedy_questions') }}"><i class="fa fa-circle-o"></i>Remedy
                                Questions</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.remedy_step') }}"><i class="fa fa-circle-o"></i>Remedy
                                Steps</a>
                        </li>
                        <li>
                            <a href="{{ route('remedy.get.tier_remedy_step') }}"><i class="fa fa-circle-o"></i>Tier
                                Remedy Steps</a>
                        </li>
                        <li>
                            <a href="{{ route('lien.law.slide.chart') }}"><i class="fa fa-circle-o"></i>Lien Law
                                Slide Chart</a>
                        </li>
                        {{-- <li><a href="{{ route('job.request.list') }}"><i class="fa fa-building-o"></i> Job Request</a></li> --}}
                    </ul>
                </li>
            @endif
            <li>
                <a href="{{ route('admin.project.view') }}">
                    <i class="fa fa-file-text-o"></i>
                    <span>Projects</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.job.info') }}">
                    <i class="fa fa-check"></i>
                    <span>Job Info Sheets</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.consultation.view') }}">
                    <i class="fa fa-comments"></i>
                    <span>Consultation</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.collectReceivable.view') }}">
                    <i class="fa fa-usd"></i>
                    <span>Collect Receivables</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.contact.view') }}">
                    <i class="fa fa-address-card"></i>
                    <span>Contact Request</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.claim.view') }}">
                    <i class="fa fa-address-card"></i>
                    <span>Claim Details</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.member.plans') }}">
                    <i class="fa fa-bell"></i> <span>Member Plans</span>
                </a>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
